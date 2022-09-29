<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Receipts;
use Illuminate\Http\Request;

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
     * @param Request $request
     */
    public function register(Request $request)
    {

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
