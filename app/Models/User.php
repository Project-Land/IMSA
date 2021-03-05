<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'current_team_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
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

    public function accidents()
    {
        return $this->hasMany('App\Models\Accident');
    }

    public function certificates()
    {
        return $this->belongsToMany('App\Models\Certificate');
    }
    
}
