<?php

namespace App\Jobs;

use App\Models\Veiculos;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteVeiculosJob implements ShouldQueue
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
        $veiculo = Veiculos::find($this->id);
        if($veiculo) $veiculo->delete();
    }
}
