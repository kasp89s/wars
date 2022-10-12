<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameUsersRequest;
use App\Mail\RecoveryMail;
use App\Models\GameUsers;
use App\Models\Receipts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ApiController extends Controller
{
    /**
     * Авторизация по коду.
     *
     * @param Request $request
     */
    public function authByCode(Request $request)
    {
        $receipt = Receipts::query()->where(['code' => $request->json('code')])->get()->first();

        return response()->json($receipt ?? []);
    }

    /**
     * Авторизация по акканту.
     *
     * @param Request $request
     */
    public function authByLogin(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'login' => 'required',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            $player = GameUsers::where('login', $request->json('login'))->first();

            if (Hash::check($request->json('password'), $player->password)) {
                // The passwords match...
                return response()->json($player);
            }

            return response()->json(['errors' => ['error' => 'Невірний логін або пароль']]);
        }

        return response()->json(['errors' => ['error' => 'Невірний логін або пароль']]);
    }

    /**
     * Пополнение по чеку.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'login' => 'required',
            'code' => 'required',
        ]);

        if ($validator->passes()) {
            $receipt = Receipts::where('code', $request->json('code'))->first();

            if (empty($receipt->id)) {
                return response()->json(['errors' => ['error' => 'Чек не знайдено']]);
            }

            $player = GameUsers::where('login', $request->json('login'))->first();
            if (empty($player->id)) {
                return response()->json(['errors' => ['error' => 'Аккаунт не знайдено']]);
            }

            $player->time = $player->time + $receipt->timeLeft;
            $player->totalTime = $player->totalTime + $receipt->timeLeft;

            $player->save();

            $receipt->timeLeft = 0;

            $receipt->save();

            return response()->json(['success' => true, 'player' => $player]);
        }

        return response()->json(['errors' => ['error' => 'Невірний код або логін']]);
    }

    /**
     * Снятие времени.
     *
     * @param Request $request
     */
    public function useTime(Request $request)
    {
        if (!empty($request->json('code'))) {
            $receipt = Receipts::query()->where(['code' => $request->json('code')])->get()->first();

            if ($receipt->timeLeft > $request->json('time')) {
                $receipt->timeLeft = $request->json('time');
                $receipt->updated_at = date('Y-m-d H:i:s', time());
                $receipt->save();
            } else {
                $receipt->timeLeft = $receipt->timeLeft - 1;

                $receipt->updated_at = date('Y-m-d H:i:s', time());

                $receipt->save();
            }

            return response()->json($receipt ?? []);
        }

        if (!empty($request->json('login')))
        {
            $player = GameUsers::where('login', $request->json('login'))->first();

            if ($player->time > $request->json('time')) {
                $player->time = $request->json('time');
                $player->updated_at = date('Y-m-d H:i:s', time());

                $player->save();
            } else {
                $player->time = $player->time - 1;
                $player->updated_at = date('Y-m-d H:i:s', time());

                $player->save();
            }

            return response()->json(['timeLeft' => $player->time]);
        }
    }

    /**
     * Регистрация.
     *
     * @param GameUsersRequest $request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'login' => 'required|unique:game_users|min:4,max:255',
            'email' => 'required|email:rfc,dns|unique:game_users|max:255',
            'password' => ['required', Password::min(6)],
        ]);

        if ($validator->passes()) {
            $player = GameUsers::create(
                [
                    'login' => $request->json('login'),
                    'email' => $request->json('email'),
                    'password' => Hash::make($request->json('password')),
                ]
            );
        } else {
            //TODO Handle your error
            return response()->json(['errors' => $validator->errors()]);
        }

        return response()->json(['success' => true, 'player' => $player]);
    }

    /**
     * Восстановление пароля.
     *
     * @param Request $request
     */
    public function recoveryPassword(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'email' => 'required|email:rfc,dns|max:255'
        ]);

        if ($validator->passes()) {
            $player = GameUsers::where('email', $request->json('email'))->first();

            if (empty($player->id)) {
                return response()->json(['errors' => ['error' => 'Аккаунт не знайдено']]);
            }

            $newPassword = Str::random(6);
            $player->password = Hash::make($newPassword);
            $player->save();

            $objDemo = new \stdClass();
            $objDemo->receiver = $player->login;
            $objDemo->password = $newPassword;

            Mail::to($player->email)->send(new RecoveryMail($objDemo));

            return response()->json(['success' => $newPassword]);
        }

        return response()->json(['errors' => $validator->errors()]);
    }
}
