<?php

namespace App\Jobs;

use App\Models\Motoristas;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteMotoristasJob implements ShouldQueue
{
    use Queueable;
    private string $id;
    /**
     * Create a new job instance.
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $motorista = Motoristas::find($this->id);
        if($motorista) $motorista->delete();
    }
}
