<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioEnderecoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "usuario_id" => 'required',
            "cep" => 'required|string|size:8',
            "rua" => 'required|string|max:50',
            "numero" => 'required|string|max:6',
            "complemento" => 'nullable|string|max:20',
            "bairro" => 'required|string|max:50',
            "cidade" => 'required|string|max:30',
            "estado" => 'required|string|size:2',
            "ponto_referencia" => 'nullable|string|max:20',
        ];
    }
}
