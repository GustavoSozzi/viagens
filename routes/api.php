<?php

use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ViagemController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/motoristas', MotoristaController::class);

Route::apiResource('/veiculos', VeiculoController::class);

Route::apiResource('/viagens', ViagemController::class);



