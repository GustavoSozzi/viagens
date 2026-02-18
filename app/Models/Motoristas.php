<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motoristas extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nome',
        'data_nascimento',
        'numero_cnh'
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_nascimento' => 'datetime'
        ];
    }

    /**
     * Relacionamento: Um motorista tem muitas viagens (N:N)
     */
    public function viagens(): BelongsToMany
    {
        return $this->belongsToMany(Viagens::class, 'motorista_viagem', 'motorista_id', 'viagem_id')
            ->withTimestamps();
    }
}
