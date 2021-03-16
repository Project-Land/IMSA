<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\PlanIpController;
use App\Http\Controllers\ManualsController;
use App\Http\Controllers\SectorsController;
use App\Http\Controllers\AccidentController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\StandardsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TrainingsController;
use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\ProceduresController;
use App\Http\Controllers\StakeholdersController;
use App\Http\Controllers\InternalCheckController;
use App\Http\Controllers\RiskManagementController;
use App\Http\Controllers\SystemProcessesController;
use App\Http\Controllers\RulesOfProceduresController;
use App\Http\Controllers\CorrectiveMeasuresController;
use App\Http\Controllers\InternalCheckReportController;
use App\Http\Controllers\MeasuringEquipmentsController;
use App\Http\Controllers\EnvironmentalAspectsController;
use App\Http\Controllers\ManagementSystemReviewsController;
use App\Http\Controllers\EvaluationOfLegalAndOtherRequirementController;
use App\Http\Controllers\SoaController;
use App\Http\Controllers\ExternalDocumentsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/standards/{id}', [HomeController::class, 'standard'])->name('standard');

    Route::resource('rules-of-procedures', RulesOfProceduresController::class);
    Route::get('rules-of-procedures-deleted', [RulesOfProceduresController::class, 'showDeleted'])->name('rules-of-procedures.deleted');
    Route::delete('rules-of-procedures-force-delete/{id}', [RulesOfProceduresController::class, 'forceDestroy'])->name('rules-of-procedures.force-destroy');
    Route::post('rules-of-procedures-restore/{id}', [RulesOfProceduresController::class, 'restore'])->name('rules-of-procedures.restore');

    Route::resource('policies', PoliciesController::class);
    Route::get('policies-deleted', [PoliciesController::class, 'showDeleted'])->name('policies.deleted');
    Route::delete('policies-force-delete/{id}', [PoliciesController::class, 'forceDestroy'])->name('policies.force-destroy');
    Route::post('policies-restore/{id}', [PoliciesController::class, 'restore'])->name('policies.restore');

    Route::get('/procedures/create', [ProceduresController::class, 'create'])->name('procedures.create');
    Route::get('/procedures/{id?}', [ProceduresController::class, 'index'])->name('procedures.index');
    Route::resource('procedures', ProceduresController::class)->except(['index']);
    Route::get('procedures-deleted', [ProceduresController::class, 'showDeleted'])->name('procedures.deleted');
    Route::delete('procedures-force-delete/{id}', [ProceduresController::class, 'forceDestroy'])->name('procedures.force-destroy');
    Route::post('procedures-restore/{id}', [ProceduresController::class, 'restore'])->name('procedures.restore');

    Route::resource('manuals', ManualsController::class);
    Route::get('manuals-deleted', [ManualsController::class, 'showDeleted'])->name('manuals.deleted');
    Route::delete('manuals-force-delete/{id}', [ManualsController::class, 'forceDestroy'])->name('manuals.force-destroy');
    Route::post('manuals-restore/{id}', [ManualsController::class, 'restore'])->name('manuals.restore');

    Route::resource('forms', FormsController::class);
    Route::get('forms-deleted', [FormsController::class, 'showDeleted'])->name('forms.deleted');
    Route::delete('forms-force-delete/{id}', [FormsController::class, 'forceDestroy'])->name('forms.force-destroy');
    Route::post('forms-restore/{id}', [FormsController::class, 'restore'])->name('forms.restore');

    Route::resource('external-documents', ExternalDocumentsController::class);
    Route::get('external-documents-deleted', [ExternalDocumentsController::class, 'showDeleted'])->name('external-documents.deleted');
    Route::delete('external-documents-force-delete/{id}', [ExternalDocumentsController::class, 'forceDestroy'])->name('external-documents.force-destroy');
    Route::post('external-documents-restore/{id}', [ExternalDocumentsController::class, 'restore'])->name('external-documents.restore');

    Route::resource('sectors', SectorsController::class);

    Route::resource('internal-check', InternalCheckController::class);
    Route::get('internal-check/get-data/{year}', [InternalCheckController::class, 'getData']);

    Route::resource('goals', GoalsController::class);
    Route::post('goals/filter-year', [GoalsController::class, 'filterYear'])->name('goals.filter-year');
    Route::post('goals/get-data', [GoalsController::class, 'getData']);
    Route::delete('goals/delete/{id}', [GoalsController::class, 'deleteApi']);

    Route::resource('risk-management', RiskManagementController::class);
    Route::get('risk-management/{id}/plan-edit', [RiskManagementController::class, 'editPlan'])->name('risk-management.edit-plan');
    Route::put('risk-management/{id}/plan-update', [RiskManagementController::class, 'updatePlan'])->name('risk-management.update-plan');
    Route::get('risk-management-export', [RiskManagementController::class, 'export'])->name('risk-management.export');

    Route::resource('corrective-measures', CorrectiveMeasuresController::class);
    Route::post('corrective-measures/store-from-icr',[CorrectiveMeasuresController::class, 'storeApi'])->name('corrective-measures.store-from-icr');
    Route::get('corrective-measures-export', [CorrectiveMeasuresController::class, 'export'])->name('corrective-measures.export');

    Route::resource('stakeholders', StakeholdersController::class);
    Route::get('stakeholders-export', [StakeholdersController::class, 'export'])->name('stakeholders.export');

    Route::resource('suppliers', SuppliersController::class);

    Route::resource('trainings', TrainingsController::class);
    Route::post('trainings/get-data', [TrainingsController::class, 'getData']);
    Route::delete('trainings/delete/{id}', [TrainingsController::class, 'deleteApi']);

    Route::resource('complaints', ComplaintsController::class);

    Route::resource('plan-ip', PlanIpController::class);

    Route::resource('internal-check-report', InternalCheckReportController::class);
    Route::get('internal-check-report/{id}/report',[InternalCheckReportController::class, 'createReport'])->name('create.report');

    Route::resource('management-system-reviews', ManagementSystemReviewsController::class);
    Route::post('management-system-reviews/get-data', [ManagementSystemReviewsController::class, 'getData']);
    Route::delete('management-system-reviews/delete/{id}', [ManagementSystemReviewsController::class, 'deleteApi']);

    Route::get('system-processes/add-to-standard', [SystemProcessesController::class, 'addToStandard'])->name('system-processes.add-to-standard');
    Route::post('system-processes/store-to-standard', [SystemProcessesController::class, 'storeToStandard'])->name('system-processes.store-to-standard');
    Route::post('system-processes/get-by-standard', [SystemProcessesController::class, 'getByStandard']);

    Route::resource('measuring-equipment', MeasuringEquipmentsController::class);

    Route::resource('environmental-aspects', EnvironmentalAspectsController::class);

    Route::get('teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('show-team-stats/{id}', [TeamController::class, 'showTeamUserStats']);

    Route::get('users/deleteApi/{id}', [UserController::class, 'deleteApi'])->name('deleteApi');
    Route::resource('users', UserController::class);
    Route::get('change-current-team/{id}', [UserController::class, 'changeCurrentTeam']);
    Route::get('user/notification-settings', [UserController::class, 'notification_settings_show'])->name('users.notification-settings');
    Route::post('user/notification-settings-save', [UserController::class, 'notification_settings'])->name('users.notification-settings-save');

    Route::get('standards/create/{id}', [StandardsController::class, 'create'])->name('standards.create-new');
    Route::resource('standards', StandardsController::class);

    Route::get('/logs/{company}', [LogsController::class, 'show'])->name('logs.show');
    Route::get('procedures/bySector/{id}', [ProceduresController::class, 'bySector'])->name('procedures.bySector');

    Route::get('analytics', [HomeController::class, 'analytics'])->name('analytics');

    Route::post('files', [HomeController::class, 'document_download'])->name('document.download');
    Route::post('file-preview', [HomeController::class, 'document_preview'])->name('document.preview');

    Route::resource('evaluation-of-requirements', EvaluationOfLegalAndOtherRequirementController::class);

    Route::resource('accidents', AccidentController::class);

    Route::resource('statement-of-applicability', SoaController::class);

    Route::get('certificates', [TeamController::class, 'getAllCertificates']);
    Route::get('user/{id}/certificates', [UserController::class, 'getUserCertificates']);
    Route::post('update-user-certificates/{id}', [UserController::class, 'updateUserCertificates']);

});

Route::get('about', [HomeController::class, 'about'])->name('about');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('manual', [HomeController::class, 'manual'])->name('manual');
Route::get('lang/{lang}', [HomeController::class, 'lang'])->name('lang');


