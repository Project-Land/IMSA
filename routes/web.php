<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\PlanIpController;
use App\Http\Controllers\ManualsController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ProceduresController;
use App\Http\Controllers\StakeholdersController;
use App\Http\Controllers\InternalCheckController;
use App\Http\Controllers\RiskManagementController;
use App\Http\Controllers\InternalCheckReportController;
use App\Http\Controllers\SectorsController;
use App\Http\Controllers\CorrectiveMeasuresController;
use App\Http\Controllers\TrainingsController;
use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\InconsistenciesController;
use App\Http\Controllers\RecommendationsController;
use App\Http\Controllers\ManagementSystemReviewsController;

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
    Route::get('/', 'App\Http\Controllers\HomeController@index')->name('dashboard');
    Route::get('/standards/{id}', 'App\Http\Controllers\HomeController@standard')->name('standard');
    Route::resource('rules-of-procedures', 'App\Http\Controllers\RulesOfProceduresController');
    Route::resource('policies', PoliciesController::class);
    Route::resource('procedures', ProceduresController::class);
    Route::resource('manuals', ManualsController::class);
    Route::resource('forms', FormsController::class);

    Route::resource('sectors', SectorsController::class);

    Route::resource('internal-check', InternalCheckController::class);
    Route::resource('goals', GoalsController::class);
    Route::post('goals/filter-year', [GoalsController::class, 'filterYear'])->name('goals.filter-year');

    Route::resource('risk-management', RiskManagementController::class);
    Route::get('risk-management/{id}/plan-edit', [RiskManagementController::class, 'editPlan'])->name('risk-management.edit-plan');
    Route::put('risk-management/{id}/plan-update', [RiskManagementController::class, 'updatePlan'])->name('risk-management.update-plan');

    Route::resource('corrective-measures', CorrectiveMeasuresController::class);
    Route::resource('stakeholders', StakeholdersController::class);
    Route::resource('suppliers', SuppliersController::class);
    Route::resource('trainings', TrainingsController::class);
    Route::post('trainings/get-data', [TrainingsController::class, 'getData']);
    Route::delete('trainings/delete/{id}', [TrainingsController::class, 'deleteApi']);
    Route::resource('complaints', ComplaintsController::class);

    Route::resource('plan-ip', PlanIpController::class);
    Route::resource('internal-check-report', InternalCheckReportController::class);
    Route::get('internal-check-report/{id}/report',[InternalCheckReportController::class,'createReport'])->name('create.report');
    Route::resource('inconsistencies', InconsistenciesController::class);
    Route::resource('recommendations', RecommendationsController::class);
    Route::resource('management-system-reviews', ManagementSystemReviewsController::class);
    
});