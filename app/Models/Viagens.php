<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Viagens extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'veiculo_id',
        'km_inicial',
        'km_final',
        'data_hora_inicial',
        'data_hora_final'
    ];

    protected function casts(): array
    {
        return [
            'data_hora_inicial' => 'datetime',
            'data_hora_final' => 'datetime',
            'km_inicial' => 'integer',
            'km_final' => 'integer'
        ];
    }

    /**
     * Relacionamento: Uma viagem pertence a um veículo
     */
    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculos::class, 'veiculo_id');
    }

    /**
     * Relacionamento: Uma viagem tem muitos motoristas (N:N)
     */
    public function motoristas(): BelongsToMany
    {
        return $this->belongsToMany(Motoristas::class, 'motorista_viagem', 'viagem_id', 'motorista_id')
            ->withTimestamps()
            ->withTrashed();
    }
}
