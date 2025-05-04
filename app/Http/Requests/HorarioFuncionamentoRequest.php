<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorarioFuncionamentoRequest extends FormRequest
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
            'restaurante_id' => 'required|exists:restaurante,id',
            'dia_semana' => 'required|string|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'hora_abertura' => 'required|date_format:H:i',
            'hora_fechamento' => 'required|date_format:H:i',
        ];
    }
}
