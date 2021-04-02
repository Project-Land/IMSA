<?php

namespace App\Providers;

use App\Models\Standard;
use App\Models\Notification;
use App\Models\SystemProcess;
use App\Observers\SectorObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
            $user = Auth::user();
            if($user->allTeams()->first()->membership->role === 'editor')
              return Notification::activeInternalChecks()->orderBy('checkTime')->get();
            else if($user->allTeams()->first()->membership->role === 'super-admin' || $user->allTeams()->first()->membership->role === 'admin')
            return Notification::active()->orderBy('checkTime')->get();
            else
                return Notification::userNots()->orderBy('checkTime')->get();
        });

        $this->app->bind('InstantNotifications', function ($app) {
            return Auth::user()->instant_notification ?? [];
        });

        $this->app->bind('CountInstantNotifications', function ($app) {
            return Auth::user()->instant_notification()->where('is_read', 0)->count() ?? 0;
        });

        $this->app->bind('standards', function ($app) {
            $teamId = Auth::user()->current_team_id;
            $standards = Standard::whereHas('teams', function($q) use ($teamId) {
                $q->where('team_id', $teamId);
             })->orderByRaw('LENGTH(name)', 'ASC')
             ->orderBy('name', 'ASC')->get();
             return $standards;
        });

        $this->app->bind('system_processes', function ($app) {
            $standard_id = session('standard');
            $system_processes = SystemProcess::whereHas('standards', function($q) use($standard_id) {
                $q->where('standard_id', $standard_id);
            })->orderBy('display_order')->get();
            return $system_processes;
        });

        session(['locale' => 'sr']);
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
