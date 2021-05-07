<?php

namespace App\Observers;

use App\Models\Sector;
use App\Facades\CustomLog;
use Illuminate\Support\Facades\Auth;

class SectorObserver
{
    /**
     * Handle the sector "created" event.
     *
     * @param  \App\Models\Sector  $sector
     * @return void
     */
    public function created(Sector $sector)
    {
        CustomLog::info('Sektor "'.$sector->name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
    }

    /**
     * Handle the sector "updated" event.
     *
     * @param  \App\Models\Sector  $sector
     * @return void
     */

    public function creating(Sector $sector){
        //
    }

    public function updated(Sector $sector)
    {
        CustomLog::info('Sektor "'.$sector->name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
    }

    /**
     * Handle the sector "deleted" event.
     *
     * @param  \App\Models\Sector  $sector
     * @return void
     */
    public function deleted(Sector $sector)
    {
        CustomLog::info('Sektor "'.$sector->name.'" obrisan, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
    }

    /**
     * Handle the sector "restored" event.
     *
     * @param  \App\Models\Sector  $sector
     * @return void
     */
    public function restored(Sector $sector)
    {
        //
    }

    /**
     * Handle the sector "force deleted" event.
     *
     * @param  \App\Models\Sector  $sector
     * @return void
     */
    public function forceDeleted(Sector $sector)
    {
        //
    }
}
