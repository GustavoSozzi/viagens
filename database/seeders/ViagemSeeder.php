<?php

namespace Database\Seeders;

use App\Models\Viagens;
use Illuminate\Database\Seeder;

class ViagemSeeder extends Seeder
{
    public function run(): void
    {
        $viagens = [
            // Viagens finalizadas
            [
                'veiculo_id' => 1,
                'km_inicial' => 80000,
                'km_final' => 80250,
                'data_hora_inicial' => '2026-01-15 08:00:00',
                'data_hora_final' => '2026-01-15 12:30:00',
                'motoristas' => [1, 2]
            ],
            [
                'veiculo_id' => 2,
                'km_inicial' => 62000,
                'km_final' => 62180,
                'data_hora_inicial' => '2026-01-16 09:00:00',
                'data_hora_final' => '2026-01-16 14:00:00',
                'motoristas' => [3]
            ],
            [
                'veiculo_id' => 3,
                'km_inicial' => 43000,
                'km_final' => 43320,
                'data_hora_inicial' => '2026-01-18 07:30:00',
                'data_hora_final' => '2026-01-18 13:45:00',
                'motoristas' => [4, 5]
            ],
            [
                'veiculo_id' => 4,
                'km_inicial' => 28000,
                'km_final' => 28150,
                'data_hora_inicial' => '2026-01-20 10:00:00',
                'data_hora_final' => '2026-01-20 14:30:00',
                'motoristas' => [6]
            ],
            [
                'veiculo_id' => 5,
                'km_inicial' => 50000,
                'km_final' => 50420,
                'data_hora_inicial' => '2026-01-22 06:00:00',
                'data_hora_final' => '2026-01-22 15:00:00',
                'motoristas' => [7, 8]
            ],
            [
                'veiculo_id' => 6,
                'km_inicial' => 70000,
                'km_final' => 70095,
                'data_hora_inicial' => '2026-01-25 11:00:00',
                'data_hora_final' => '2026-01-25 13:30:00',
                'motoristas' => [1]
            ],
            [
                'veiculo_id' => 7,
                'km_inicial' => 23000,
                'km_final' => 23280,
                'data_hora_inicial' => '2026-01-28 08:30:00',
                'data_hora_final' => '2026-01-28 14:00:00',
                'motoristas' => [2, 3]
            ],
            [
                'veiculo_id' => 8,
                'km_inicial' => 36000,
                'km_final' => 36210,
                'data_hora_inicial' => '2026-02-01 09:00:00',
                'data_hora_final' => '2026-02-01 15:30:00',
                'motoristas' => [4]
            ],
            
            // Viagens em andamento (sem km_final e data_hora_final)
            [
                'veiculo_id' => 9,
                'km_inicial' => 47000,
                'km_final' => null,
                'data_hora_inicial' => '2026-02-08 07:00:00',
                'data_hora_final' => null,
                'motoristas' => [5, 6]
            ],
            [
                'veiculo_id' => 10,
                'km_inicial' => 14000,
                'km_final' => null,
                'data_hora_inicial' => '2026-02-08 08:30:00',
                'data_hora_final' => null,
                'motoristas' => [7]
            ],
        ];

        foreach ($viagens as $viagemData) {
            $motoristas = $viagemData['motoristas'];
            unset($viagemData['motoristas']);
            
            $viagem = Viagens::create($viagemData);
            $viagem->motoristas()->attach($motoristas);
        }
    }
}
