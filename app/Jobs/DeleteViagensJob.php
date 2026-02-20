<?php

namespace App\Jobs;

use App\Events\DeleteTripsProcessed;
use App\Models\Viagens;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteViagensJob implements ShouldQueue
{
    use Queueable;
    private string $id;

    public function __construct(string $id)
    {

        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('Job: Iniciando exclusão', ['id' => $this->id]);
        
        $viagem = Viagens::find($this->id);

        if($viagem) {
            \Log::info('Job: Viagem encontrada, deletando', ['id' => $this->id]);
            $viagem->delete(); //dispara o observer
            \Log::info('Job: Viagem deletada', ['id' => $this->id]);
        } else {
            \Log::warning('Job: Viagem não encontrada', ['id' => $this->id]);
        }
    }
}
