<?php

namespace App\Observers;

use App\Events\DeleteTripsProcessed;
use App\Events\DeleteVehicleProcessed;
use App\Models\Veiculos;

class VehicleObserver
{
    /**
     * Handle the Veiculos "created" event.
     */
    public function created(Veiculos $veiculos): void
    {
        //
    }

    /**
     * Handle the Veiculos "updated" event.
     */
    public function updated(Veiculos $veiculos): void
    {
        //
    }

    /**
     * Handle the Veiculos "deleted" event.
     */
    public function deleted(Veiculos $veiculos): void
    {
        event(new DeleteVehicleProcessed($veiculos->id));
    }

    /**
     * Handle the Veiculos "restored" event.
     */
    public function restored(Veiculos $veiculos): void
    {
        //
    }

    /**
     * Handle the Veiculos "force deleted" event.
     */
    public function forceDeleted(Veiculos $veiculos): void
    {
        //
    }
}
