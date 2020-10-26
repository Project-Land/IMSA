<?php

namespace App\Providers;

use App\Models\CorrectiveMeasure;
use App\Models\Document;
use App\Models\InternalCheck;
use App\Models\InternalCheckReport;
use App\Models\ManagementSystemReview;
use App\Models\PlanIp;
use App\Models\RiskManagement;
use App\Models\Team;
use App\Policies\CorrectiveMeasurePolicy;
use App\Policies\DocumentPolicy;
use App\Policies\GoalPolicy;
use App\Policies\InternalCheckPolicy;
use App\Policies\InternalCheckReportPolicy;
use App\Policies\ManagementSystemReviewPolicy;
use App\Policies\PlanIpPolicy;
use App\Policies\RiskManagementPolicy;
use App\Policies\TeamPolicy;
use App\Policies\SectorPolicy;
use App\Models\Sector;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        Stakeholder::class => StakeholderPolicy::class,
        Supplier::class => SupplierPolicy::class,
        Complaint::class => ComplaintPolicy::class,
        Sector::class => SectorPolicy::class,
        CorrectiveMeasure::class => CorrectiveMeasurePolicy::class,
        Document::class => DocumentPolicy::class,
        Goal::class => GoalPolicy::class,
        InternalCheck::class => InternalCheckPolicy::class,
        InternalCheckReport::class => InternalCheckReportPolicy::class,
        ManagementSystemReview::class => ManagementSystemReviewPolicy::class,
        RiskManagement::class => RiskManagementPolicy::class,
        PlanIp::class => PlanIpPolicy::class
        
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
