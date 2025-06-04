<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AutenticacaoController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciais inválidas',
            ], 200);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Deslogado com sucesso',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function socialiteRedirect($social)
    {
        return Socialite::driver($social)->stateless()->redirect();
    }

    public function socialiteCallback(Request $request, $social)
    {
        $facebookUser = Socialite::driver($social)->stateless()->user();

        $user = Usuario::where('email', $facebookUser->email)->first();

        if(!$user) {
            $user = Usuario::updateOrCreate(
                ['facebook_id' => $facebookUser->id],
                [
                    'facebook_id' => $facebookUser->id,
                    'nome_completo' => $facebookUser->name,
                    'email' => $facebookUser->email,
                ]
            );
        }


        $token = Auth::guard('api_usuario')->login($user);
        $user->update(['token' => $token]);

        return redirect("https://localhost:3000/restaurantes?token={$token}");
    }

    public function googleSignInMobile(Request $request)
    {
        $data = $request->all();

        $user = Usuario::where('email', $data['email'])->first();

        if(!$user) {
            $user = Usuario::updateOrCreate(
                ['google_id' => $data['id']],
                [
                    'google_id' => $data['id'],
                    'nome_completo' => $data['name'],
                    'email' => $data['email'],
                ]
            );
        }
        //teste

        $token = Auth::guard('api_usuario')->login($user);
        $user->update(['token' => $token]);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);;
    }
}
