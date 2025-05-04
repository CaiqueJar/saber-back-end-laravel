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
            'nome' => 'nullable|string|max:60',
            'descritivo' => 'nullable|string',
            'imagem' => 'nullable|string|max:255',
            'preco' => 'nullable|string|between:0,99999999.99',
            'disponibilidade' => 'nullable|boolean',
            'desconto' => 'nullable|string|between:0,99999999.99',
            'status' => 'nullable|in:disponivel,indisponivel',
        ];
    }
}
