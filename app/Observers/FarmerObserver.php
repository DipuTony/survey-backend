<?php

namespace App\Observers;

use App\Models\Survey\Farmer;

class FarmerObserver
{
    /**
     * Handle the Farmer "created" event.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return void
     */
    public function created(Farmer $farmer)
    {
        $farmer->farmer_id = 'SURVEY-' . $farmer->id;
        $farmer->save();
    }

    /**
     * Handle the Farmer "updated" event.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return void
     */
    public function updated(Farmer $farmer)
    {
        //
    }

    /**
     * Handle the Farmer "deleted" event.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return void
     */
    public function deleted(Farmer $farmer)
    {
        //
    }

    /**
     * Handle the Farmer "restored" event.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return void
     */
    public function restored(Farmer $farmer)
    {
        //
    }

    /**
     * Handle the Farmer "force deleted" event.
     *
     * @param  \App\Models\Farmer  $farmer
     * @return void
     */
    public function forceDeleted(Farmer $farmer)
    {
        //
    }
}
