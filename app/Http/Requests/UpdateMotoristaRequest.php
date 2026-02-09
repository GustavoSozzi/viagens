<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMotoristaRequest extends FormRequest
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
        $motoristaId = $this->route('motorista');

        return [
            "nome" => "required",
            "data_nascimento" => "required|date|before_or_equal:" . now()->subYears(18)->toDateString(),
            "numero_cnh" => "required|unique:motoristas,numero_cnh," . $motoristaId . "|digits:11"
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
            'numero_cnh.unique' => 'Este número de CNH já está cadastrado.',
            'numero_cnh.digits' => 'O número da CNH deve ter exatamente 11 dígitos.',
            'data_nascimento.before_or_equal' => 'O motorista deve ter pelo menos 18 anos.',
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
