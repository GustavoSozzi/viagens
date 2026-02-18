<?php

namespace App\Jobs;

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
        $viagem = Viagens::find($this->id);
        if($viagem) $viagem->delete();
    }
}
