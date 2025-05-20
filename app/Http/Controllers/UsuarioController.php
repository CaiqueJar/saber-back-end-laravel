<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioEnderecoRequest;
use App\Http\Requests\UsuarioRequest;
use App\Http\Requests\UsuarioUpdateRequest;
use App\Mail\CodigoOTPMail;
use App\Mail\UsuarioCadastradoMail;
use App\Models\CodigoOtp;
use App\Models\EnderecoUsuario;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    private CodigoOtp $codigoOtp;

    public function __construct(CodigoOtp $codigoOtp)
    {
        $this->codigoOtp = $codigoOtp;
    }

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
        
        $this->codigoOtp->where('email', $email)
            ->where('tipo', 'cliente')
            ->delete();

        $this->codigoOtp->create([
            'email' => $email,
            'codigo' => $codigo,
            'tipo' => 'cliente',
            'expira_em' => now()->addMinutes(15),
        ]);
        
        return response()->json(['success' => 'Um e-mail com o código foi enviado.', 200]);
    }

    public function validarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|size:6', 
            'email' => 'required|email'
        ]);

        $email = $request->input('email');
        $codigo = $request->input('codigo');

        $codigoOtp = $this->codigoOtp->where('email', $email)->where('codigo', $codigo)->first();

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
        
        $codigoOtp->delete();

        return response()->json([
            'status' => 'success',
            'user' => $usuario,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function cadastrar(UsuarioRequest $request)
    {
        $data = $request->validated();

        $data['senha'] = Hash::make($data['senha']);

        $usuario = Usuario::create($data);

        $token = Auth::guard('api_usuario')->login($usuario);
        
        try {
            Mail::to($usuario->email)->send(new UsuarioCadastradoMail);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
        
        return response()->json([
            'status' => 'success',
            'user' => $usuario,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function cadastrarEndereco(UsuarioEnderecoRequest $request)
    {
        $data = $request->validated();

        $usuario = Usuario::find($data['usuario_id']);

        if(!$usuario) {
            return response()->json(['error' => 'Usuário não encontrado']);
        }

        EnderecoUsuario::create($data);

        return response()->json(['success' => 'Endereço cadastrado com sucesso!']);
    }

    public function atualizar(UsuarioUpdateRequest $request)
    {
        $data = $request->validated();

        $usuario = Usuario::find($data['usuario_id']);

        if(!$usuario) {
            return response()->json(['error' => 'Usuário não encontrado']);
        }

        if($data['senha'] != null && $data['senha'] != '') {
            $usuario->update([
                'senha' => Hash::make($data['senha'])
            ]);
            unset($data['senha']);
        } 

        $usuario->update($data);
        

        return response()->json($usuario, 200);
    }
}
