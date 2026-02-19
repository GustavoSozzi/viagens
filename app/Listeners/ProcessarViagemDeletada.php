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
        ViagensJob::dispatch($event->viagens)->onQueue('jobs');
    }
}
