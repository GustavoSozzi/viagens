<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotoristaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'nome' => $this->nome,
            'data_nascimento' => $this->data_nascimento,
            'numero_cnh' => $this->numero_cnh,
        ];
    }
}
