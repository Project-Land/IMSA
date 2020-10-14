<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\ManualsController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\ProceduresController;
use App\Http\Controllers\InternalCheckController;
use App\Http\Controllers\RiskManagementController;
use App\Http\Controllers\ComplianceCorrectionsController;
use App\Http\Controllers\StakeholdersController;


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

    Route::resource('internal-check', InternalCheckController::class);
    Route::resource('goals', GoalsController::class);

    Route::resource('risk-management', RiskManagementController::class);
    Route::get('risk-management/{id}/plan-edit', [RiskManagementController::class, 'editPlan'])->name('risk-management.edit-plan');
    Route::put('risk-management/{id}/plan-update', [RiskManagementController::class, 'updatePlan'])->name('risk-management.update-plan');
    Route::resource('compliance-correction', ComplianceCorrectionsController::class);
    Route::resource('stakeholders', StakeholdersController::class);

});
