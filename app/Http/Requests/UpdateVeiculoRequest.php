<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateVeiculoRequest extends FormRequest
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
        // Pega o ID do último segmento da URL (ex: /api/veiculos/4)
        $veiculoId = $this->route()->parameter('veiculo') ?? request()->segment(3);

        return [
            "modelo" => "required",
            "ano" => "required|integer",
            "data_aquisicao" => "required|date",
            "kms_rodados" => "required|integer",
            "renavam" => "required|string|unique:veiculos,renavam," . $veiculoId,
            "placa" => "required|string|unique:veiculos,placa," . $veiculoId
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'renavam.unique' => 'Este RENAVAM já está cadastrado.',
            'placa.unique' => 'Esta placa já está cadastrada.',
            'ano.integer' => 'O ano deve ser um número inteiro.',
            'kms_rodados.integer' => 'Os quilômetros rodados devem ser um número inteiro.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
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
