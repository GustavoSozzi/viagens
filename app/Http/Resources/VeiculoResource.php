<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VeiculoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'modelo' => $this->modelo,
            'ano' => $this->ano,
            'data_aquisicao' => $this->data_aquisicao,
            'kms_rodados' => $this->kms_rodados,
            'renavam' => $this->renavam,
            'placa' => $this->placa,
        ];
    }
}
