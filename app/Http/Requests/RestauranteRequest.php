<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestauranteRequest extends FormRequest
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
            'nome_completo_login' => 'nullable|string|max:150',
            'email' => 'required|email|unique:restaurante,email',
            'senha' => 'required|string|min:8',
            'celular' => 'nullable|max:11',
            'cnpj' => 'required|regex:/^\d{14}$/|unique:restaurante,cnpj',
            'razao_social' => 'required|string|max:60',
            'nome_loja' => 'required|string|max:60',
            'telefone_celular' => 'required|max:11',
            'categoria_id' => 'nullable|exists:categoria_restaurante,id',
            'pedido_minimo' => 'nullable|numeric|min:0',
            'taxa_entrega' => 'required|numeric|min:0',
            'cep' => 'required|size:8',
            'logradouro' => 'required|string|max:150',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:50',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'estado' => 'required|string|size:2',
        ];
    }
}
