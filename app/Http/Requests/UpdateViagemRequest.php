<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateViagemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'veiculo_id' => 'required|exists:veiculos,id',
            'motoristas' => 'required|array|min:1',
            'motoristas.*' => 'exists:motoristas,id',
            'km_inicial' => 'required|integer|min:0',
            'km_final' => 'nullable|integer|min:0|gte:km_inicial',
            'data_hora_inicial' => 'required|date',
            'data_hora_final' => 'nullable|date|after:data_hora_inicial'
        ];
    }

    public function messages(): array
    {
        return [
            'veiculo_id.required' => 'O veículo é obrigatório.',
            'veiculo_id.exists' => 'O veículo selecionado não existe.',
            'motoristas.required' => 'Pelo menos um motorista deve ser selecionado.',
            'motoristas.*.exists' => 'Um ou mais motoristas selecionados não existem.',
            'km_inicial.required' => 'O KM inicial é obrigatório.',
            'km_final.gte' => 'O KM final deve ser maior ou igual ao KM inicial.',
            'data_hora_inicial.required' => 'A data e hora inicial são obrigatórias.',
            'data_hora_final.after' => 'A data e hora final deve ser posterior à inicial.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
