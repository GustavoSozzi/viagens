<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViagemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'veiculo_id' => $this->veiculo_id,
            'motoristas' => MotoristaResource::collection($this->whenLoaded('motoristas')),
            'km_inicial' => $this->km_inicial,
            'km_final' => $this->km_final,
            'data_hora_inicial' => $this->data_hora_inicial,
            'data_hora_final' => $this->data_hora_final,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
