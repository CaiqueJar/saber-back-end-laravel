<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MpController extends Controller
{
    public function connectToMercadoPago()
    {
        $client_id = env('MP_CLIENT_ID');
        $redirect_uri = route('mp.return');

        $link = "https://auth.mercadopago.com.br/authorization?client_id={$client_id}&response_type=code&platform_id=mp&redirect_uri={$redirect_uri}";

        return redirect($link);
    }

    public function redirect(Request $request)
    {
        info($request->all());
    }

    public function webhook(Request $request)
    {
        info($request->all());
        return response()->json($request->all());
    }
}
