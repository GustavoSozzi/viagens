<?php

namespace Database\Seeders;

use App\Models\Veiculos;
use Illuminate\Database\Seeder;

class VeiculoSeeder extends Seeder
{
    public function run(): void
    {
        $veiculos = [
            ['modelo' => 'Fiat Uno', 'ano' => 2018, 'data_aquisicao' => '2018-03-15', 'kms_rodados' => 85000, 'renavam' => '12345678901', 'placa' => 'ABC1234'],
            ['modelo' => 'Volkswagen Gol', 'ano' => 2019, 'data_aquisicao' => '2019-06-20', 'kms_rodados' => 65000, 'renavam' => '12345678902', 'placa' => 'DEF5678'],
            ['modelo' => 'Chevrolet Onix', 'ano' => 2020, 'data_aquisicao' => '2020-01-10', 'kms_rodados' => 45000, 'renavam' => '12345678903', 'placa' => 'GHI9012'],
            ['modelo' => 'Fiat Argo', 'ano' => 2021, 'data_aquisicao' => '2021-08-05', 'kms_rodados' => 30000, 'renavam' => '12345678904', 'placa' => 'JKL3456'],
            ['modelo' => 'Hyundai HB20', 'ano' => 2020, 'data_aquisicao' => '2020-11-12', 'kms_rodados' => 52000, 'renavam' => '12345678905', 'placa' => 'MNO7890'],
            ['modelo' => 'Renault Kwid', 'ano' => 2019, 'data_aquisicao' => '2019-04-22', 'kms_rodados' => 72000, 'renavam' => '12345678906', 'placa' => 'PQR1234'],
            ['modelo' => 'Toyota Corolla', 'ano' => 2022, 'data_aquisicao' => '2022-02-18', 'kms_rodados' => 25000, 'renavam' => '12345678907', 'placa' => 'STU5678'],
            ['modelo' => 'Honda Civic', 'ano' => 2021, 'data_aquisicao' => '2021-09-30', 'kms_rodados' => 38000, 'renavam' => '12345678908', 'placa' => 'VWX9012'],
            ['modelo' => 'Nissan Versa', 'ano' => 2020, 'data_aquisicao' => '2020-07-14', 'kms_rodados' => 48000, 'renavam' => '12345678909', 'placa' => 'YZA3456'],
            ['modelo' => 'Jeep Renegade', 'ano' => 2023, 'data_aquisicao' => '2023-01-25', 'kms_rodados' => 15000, 'renavam' => '12345678910', 'placa' => 'BCD7890'],
        ];

        foreach ($veiculos as $veiculo) {
            Veiculos::create($veiculo);
        }
    }
}
