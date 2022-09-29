<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameUsersRequest;
use App\Models\GameUsers;
use App\Models\Receipts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
     * Снятие времени.
     *
     * @param Request $request
     */
    public function useTime(Request $request)
    {
        $receipt = Receipts::query()->where(['code' => $request->json('code')])->get()->first();

        if ($receipt->timeLeft > $request->json('time')) {
            $receipt->timeLeft = $request->json('time');

            $receipt->save();
        } else {
            $receipt->timeLeft = $receipt->timeLeft - 1;

            $receipt->save();
        }

        return response()->json($receipt ?? []);
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

    }
}
