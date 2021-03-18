<?php

namespace App\Models;

use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'personal_team',
        'lang'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];


    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }

    public function correctiveMeasures()
    {
        return $this->hasMany('App\Models\CorrectiveMeasure');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document');
    }

    public function goals()
    {
        return $this->hasMany('App\Models\Goal');
    }

    public function internalChecks()
    {
        return $this->hasMany('App\Models\InternalCheck');
    }
    public function internalCheckReport()
    {
        return $this->hasMany('App\Models\InternalCheckReport');
    }

    public function managamentSystemReviews()
    {
        return $this->hasMany('App\Models\ManagamentSystemReview');
    }
    public function riskManagement()
    {
        return $this->hasMany('App\Models\RiskManagement');
    }

    public function sectors()
    {
        return $this->hasMany('App\Models\Sector');
    }

    public function stakeholders()
    {
        return $this->hasMany('App\Models\Stakeholder');
    }

    public function suppliers()
    {
        return $this->hasMany('App\Models\Supplier');
    }

    public function trainings()
    {
        return $this->hasMany('App\Models\Training');
    }

    public function standards()
    {
        return $this->belongsToMany('App\Models\Standard');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function accidents()
    {
        return $this->hasMany('App\Models\Accident');
    }

    public function satisfactionColumns()
    {
        return $this->hasMany('App\Models\SatisfactionColumn');
    }

    public function customerSatisfactions()
    {
        return $this->hasMany('App\Models\CustomerSatisfaction');
    }

}
