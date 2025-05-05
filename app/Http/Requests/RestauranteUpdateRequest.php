<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestauranteUpdateRequest extends FormRequest
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
            'nome_loja' => 'string|max:60',
            'telefone_celular' => 'max:11',
            'categoria_id' => 'exists:categoria_restaurante,id',
            'pedido_minimo' => 'numeric|min:0',
            'taxa_entrega' => 'numeric|min:0',
            'logo' => 'nullable',
            'banner' => 'nullable',
            'endereco' => 'array',
            'endereco.cep' => 'size:8',
            'endereco.logradouro' => 'string|max:150',
            'endereco.numero' => 'string|max:10',
            'endereco.complemento' => 'nullable|string|max:50',
            'endereco.bairro' => 'string|max:50',
            'endereco.cidade' => 'string|max:50',
            'endereco.estado' => 'string|size:2',
        ];
    }
}
