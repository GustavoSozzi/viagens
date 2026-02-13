<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veiculos extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'modelo',
        'ano',
        'data_aquisicao',
        'kms_rodados',
        'renavam',
        'placa'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
     protected function casts(): array
    {
        return [
            'data_aquisicao' => 'datetime',
            'ano' => 'integer',
            'kms_rodados' => 'integer'
        ];
    }

    /**
     * Relacionamento: Um veículo tem muitas viagens
     */
    public function viagens(): HasMany
    {
        return $this->hasMany(Viagens::class, 'veiculo_id');
    }

}
