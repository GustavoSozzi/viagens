<?php

namespace Database\Seeders;

use App\Models\Motoristas;
use Illuminate\Database\Seeder;

class MotoristaSeeder extends Seeder
{
    public function run(): void
    {
        $motoristas = [
            ['nome' => 'João Silva', 'data_nascimento' => '1985-03-15', 'numero_cnh' => '12345678901'],
            ['nome' => 'Maria Santos', 'data_nascimento' => '1990-07-22', 'numero_cnh' => '23456789012'],
            ['nome' => 'Pedro Oliveira', 'data_nascimento' => '1988-11-10', 'numero_cnh' => '34567890123'],
            ['nome' => 'Ana Costa', 'data_nascimento' => '1992-05-18', 'numero_cnh' => '45678901234'],
            ['nome' => 'Carlos Souza', 'data_nascimento' => '1987-09-25', 'numero_cnh' => '56789012345'],
            ['nome' => 'Juliana Lima', 'data_nascimento' => '1995-01-30', 'numero_cnh' => '67890123456'],
            ['nome' => 'Roberto Alves', 'data_nascimento' => '1983-12-08', 'numero_cnh' => '78901234567'],
            ['nome' => 'Fernanda Rocha', 'data_nascimento' => '1991-04-14', 'numero_cnh' => '89012345678'],
        ];

        foreach ($motoristas as $motorista) {
            Motoristas::create($motorista);
        }
    }
}
