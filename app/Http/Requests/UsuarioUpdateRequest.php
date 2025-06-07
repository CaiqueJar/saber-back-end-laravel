<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioUpdateRequest extends FormRequest
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
            'nome_completo' => 'string|max:150',
            'email' => 'email',
            'cpf' => 'nullable|size:11',
            'telefone_celular' => 'nullable|min:10|max:11'
        ];
    }
}
