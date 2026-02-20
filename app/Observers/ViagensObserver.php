<?php

namespace App\Observers;

use App\Events\DeleteTripsProcessed;
use App\Models\Viagens;

class ViagensObserver
{
    /**
     * Handle the Viagens "created" event.
     */
    public function created(Viagens $viagens): void
    {
        //
    }

    /**
     * Handle the Viagens "updated" event.
     */
    public function updated(Viagens $viagens): void
    {
        //
    }

    /**
     * Handle the Viagens "deleted" event.
     */
    public function deleted(Viagens $viagens): void
    {
        event(new DeleteTripsProcessed($viagens->id));
    }

    /**
     * Handle the Viagens "restored" event.
     */
    public function restored(Viagens $viagens): void
    {
        //
    }

    /**
     * Handle the Viagens "force deleted" event.
     */
    public function forceDeleted(Viagens $viagens): void
    {
        //
    }
}
