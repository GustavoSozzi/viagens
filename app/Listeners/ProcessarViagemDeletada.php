<?php

namespace App\Listeners;

use App\Events\DeleteTripsProcessed;
use App\Jobs\ViagensJob;

class ProcessarViagemDeletada
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeleteTripsProcessed $event): void
    {
        // O evento já foi disparado, não precisa fazer nada aqui
        // O broadcast já foi feito automaticamente
    }
}
