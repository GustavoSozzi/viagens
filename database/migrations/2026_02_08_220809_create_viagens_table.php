<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('viagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            $table->integer('km_inicial');
            $table->integer('km_final')->nullable();
            $table->dateTime('data_hora_inicial');
            $table->dateTime('data_hora_final')->nullable();
            $table->timestamps();
        });

        // Tabela pivot para relacionamento N:N entre Viagens e Motoristas
        Schema::create('motorista_viagem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viagem_id')->constrained('viagens')->onDelete('cascade');
            $table->foreignId('motorista_id')->constrained('motoristas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorista_viagem');
        Schema::dropIfExists('viagens');
    }
};
