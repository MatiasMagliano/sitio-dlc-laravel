<?php

namespace App\Observers;

use App\Models\Lote;

class LoteObserver
{
    /**
     * Handle the Lote "created" event.
     *
     * @param  \App\Models\Lote  $lote
     * @return void
     */
    public function created(Lote $lote)
    {
        $lote->presentacion()->increment('stock', $lote->cantidad);
    }

    /**
     * Handle the Lote "updated" event.
     *
     * @param  \App\Models\Lote  $lote
     * @return void
     */
    public function updated(Lote $lote)
    {
        if($lote->presentacion()->isDirty('stock'))
        {
            $cantidad = $lote->getOriginal('cantidad') - $lote->cantidad;
            $lote->presentacion()->decrement('stock', $cantidad);
        }
    }

    /**
     * Handle the Lote "deleted" event.
     *
     * @param  \App\Models\Lote  $lote
     * @return void
     */
    public function deleted(Lote $lote)
    {
        //
    }

    /**
     * Handle the Lote "restored" event.
     *
     * @param  \App\Models\Lote  $lote
     * @return void
     */
    public function restored(Lote $lote)
    {
        //
    }

    /**
     * Handle the Lote "force deleted" event.
     *
     * @param  \App\Models\Lote  $lote
     * @return void
     */
    public function forceDeleted(Lote $lote)
    {
        //
    }
}