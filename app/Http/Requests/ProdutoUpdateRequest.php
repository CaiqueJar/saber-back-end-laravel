<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoUpdateRequest extends FormRequest
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
            'nome' => 'string|max:60',
            'descritivo' => 'string',
            'imagem' => 'string|max:255',
            'preco' => 'string|between:0,99999999.99',
            'disponibilidade' => 'boolean',
            'desconto' => 'string|between:0,99999999.99',
            'status' => 'in:disponivel,indisponivel',
        ];
    }
}
