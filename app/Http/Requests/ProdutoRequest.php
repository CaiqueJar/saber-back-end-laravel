<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
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
            'categoria_id' => 'required|integer|exists:categoria_restaurante,id',
            'nome' => 'required|string|max:60',
            'descritivo' => 'nullable|string',
            'imagem' => 'required',
            'preco' => 'required|string|between:0,99999999.99',
            'disponibilidade' => 'required|boolean',
            'desconto' => 'nullable|string|between:0,99999999.99',
            'status' => 'required|in:disponivel,indisponivel',
        ];
    }
}
