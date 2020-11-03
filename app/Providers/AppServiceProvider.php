<?php

namespace App\Providers;

use App\Models\Standard;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use App\Observers\SectorObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Notifications', function ($app) {
            $user=Auth::user();
            if($user->allTeams()->first()->membership->role==='editor')
              return Notification::activeInternalChecks()->get();
            else if($user->allTeams()->first()->membership->role==='super-admin' || $user->allTeams()->first()->membership->role==='admin')
                return Notification::active()->get();
            else 
                return [];
        });

        $this->app->bind('standards', function ($app) {
            $teamId = \Auth::user()->current_team_id;
            $standards = Standard::whereHas('teams', function($q) use ($teamId) {
                $q->where('team_id', $teamId);
             })->get();
             return $standards;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultstringLength(191);
        \App\Models\Sector::observe(SectorObserver::class);
    }
}
