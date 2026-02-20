<?php

use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ViagemController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Rota de autenticação do broadcasting
Broadcast::routes(['middleware' => ['api']]);

Route::apiResource('/motoristas', MotoristaController::class);

Route::apiResource('/veiculos', VeiculoController::class);

Route::apiResource('/viagens', ViagemController::class);



