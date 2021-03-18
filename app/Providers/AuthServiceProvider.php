<?php

namespace App\Providers;

use App\Models\Accident;
use App\Models\CorrectiveMeasure;
use App\Models\Document;
use App\Models\InternalCheck;
use App\Models\InternalCheckReport;
use App\Models\ManagementSystemReview;
use App\Models\PlanIp;
use App\Models\RiskManagement;
use App\Models\Team;
use App\Models\Training;
use App\Models\Sector;
use App\Models\Supplier;
use App\Models\Stakeholder;
use App\Models\Complaint;
use App\Models\EvaluationOfLegalAndOtherRequirement;
use App\Models\MeasuringEquipment;
use App\Models\EnvironmentalAspect;
use App\Models\Notification;
use App\Models\User;
use App\Models\Soa;
use App\Models\Standard;
use App\Models\SystemProcess;
use App\Models\CustomerSatisfaction;
use App\Models\SatisfactionColumn;
use App\Policies\AccidentPolicy;
use App\Policies\CorrectiveMeasurePolicy;
use App\Policies\DocumentPolicy;
use App\Policies\GoalPolicy;
use App\Policies\InternalCheckPolicy;
use App\Policies\InternalCheckReportPolicy;
use App\Policies\ManagementSystemReviewPolicy;
use App\Policies\PlanIpPolicy;
use App\Policies\RiskManagementPolicy;
use App\Policies\TeamPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\StakeholderPolicy;
use App\Policies\SectorPolicy;
use App\Policies\TrainingPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\EvaluationOfLegalAndOtherRequirementPolicy;
use App\Policies\MeasuringEquipmentPolicy;
use App\Policies\EnvironmentalAspectPolicy;
use App\Policies\UserPolicy;
use App\Policies\StandardPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\SystemProcessPolicy;
use App\Policies\SoaPolicy;
use App\Policies\CustomerSatisfactionPolicy;
use App\Policies\SatisfactionColumnsPolicy;
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
        PlanIp::class => PlanIpPolicy::class,
        Training::class => TrainingPolicy::class,
        MeasuringEquipment::class => MeasuringEquipmentPolicy::class,
        EnvironmentalAspect::class => EnvironmentalAspectPolicy::class,
        SystemProcess::class => SystemProcessPolicy::class,
        Notification::class => NotificationPolicy::class,
        Standard::class => StandardPolicy::class,
        User::class => UserPolicy::class,
        EvaluationOfLegalAndOtherRequirement::class => EvaluationOfLegalAndOtherRequirementPolicy::class,
        Accident::class => AccidentPolicy::class,
        Soa::class => SoaPolicy::class,
        CustomerSatisfaction::class => CustomerSatisfactionPolicy::class,
        SatisfactionColumn::class => SatisfactionColumnsPolicy::class

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
