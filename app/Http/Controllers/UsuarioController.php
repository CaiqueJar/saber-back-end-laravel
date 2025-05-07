<?php

namespace App\Http\Controllers;

use App\Mail\CodigoOTPMail;
use App\Models\CodigoOtp;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    public function enviarEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->input('email');
        $codigo = random_int(100000, 999999);


        try {
            Mail::to($email)->send(new CodigoOTPMail($codigo));
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }

        CodigoOtp::create([
            'email' => $email,
            'codigo' => $codigo,
            'tipo' => 'cliente',
            'expira_em' => now()->addMinutes(15),
        ]);
        
        return response()->json(['success' => 'Um e-mail com o código foi enviado.', 200]);
    }

    public function validarCodigo(Request $request)
    {
        $request->validate(['codigo' => 'required|string|size:6', 'email' => 'required|email']);
        $email = $request->input('email');
        $codigo = $request->input('codigo');

        $codigoOtp = CodigoOtp::where('email', $email)->where('codigo', $codigo)->first();

        if(!$codigoOtp) {
            return response()->json(['error' => 'Código otp não encontrado!'], 200);
        }

        if($codigoOtp->expira_em < now()) {
            return response()->json(['error' => 'Código otp expirou!'], 200);
        }

        $usuario = Usuario::where('email', $email)->first();

        if(!$usuario) {
            return response()->json(['success' => 'Código otp verificado com sucesso!', 'novaconta' => true]);
        }

        $token = Auth::guard('api_usuario')->login($usuario);
        
        return response()->json([
            'status' => 'success',
            'user' => $usuario,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
}
