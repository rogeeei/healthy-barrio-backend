<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\CitizenDetailsController;
use App\Http\Controllers\CitizenHistoryController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SummaryReportController;
use App\Http\Controllers\CitizenServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Public API
Route::post('/login', [AuthController::class,  'login'])->name('user.login');
Route::post('/user', [UserController::class,   'store'])->name('user.store');
Route::get('/demo-summary', [SummaryReportController::class, 'getDemographicSummary']);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services/{serviceId}/age-distribution', [SummaryReportController::class, 'getServiceWithAgeDistribution']);



//Private API
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    //Admin APIs
    Route::post('/admin/users', [AdminController::class, 'addUser']);
    Route::patch('/user/{user}/approve', [AdminController::class, 'approveUser']);
    Route::patch('/user/decline/{id}', [AdminController::class, 'declineUser']);

    Route::controller(ServicesController::class)->group(function () {
        Route::post('/services',                 'store');
        Route::delete('/services/{id}',          'destroy');
        Route::get('/summary',                  'showServicesSummary');
    });


    Route::controller(CitizenDetailsController::class)->group(function () {
        Route::get('/citizen',                   'index');
        Route::get('/citizen-history',           'index');
        Route::post('/citizen',                  'store');
        Route::delete('/citizen/{id}',           'destroy');
        Route::put('/citizen/{id}',              'update');
        Route::get('/citizen/{id}',              'show');
        Route::get('/citizen/{id}/diagnostics',  'getDiagnostics');
        Route::get('/services-summary',          'getServicesSummary');
        Route::get('/citizen-overview',          'getCitizenVisitHistory');
        Route::get('/service-view',                  'fetchServicesView');
        Route::get('/service-index',                  'getCitizens');
    });

    Route::controller(CitizenHistoryController::class)->group(function () {
        Route::get('/specified-history',                 'index');
        Route::get('/citizen-history/{id}',             'show');
        Route::get('/monthly-history',                  'getHistoryByMonth');
        Route::get('/transaction-history/{citizenId}',  'getTransactionHistory');
    });

    Route::controller(DiagnosticController::class)->group(function () {
        Route::get('/diagnostics',               'index');
        Route::post('/diagnostics',               'store');
        Route::delete('/diagnostics/{id}',          'destroy');
    });

    Route::controller(MedicineController::class)->group(function () {
        Route::post('/medicine',                 'store');
        Route::get('/medicine/{id}',               'show');
        Route::put('/medicine/{id}',             'update');
        Route::delete('/medicine/{id}',          'destroy');
        Route::get('/medicine',                  'index');
    });

    Route::controller(EquipmentController::class)->group(function () {
        Route::get('/equipment',                   'index');
        Route::post('/equipment',                 'store');
        Route::get('/equipment/{id}',               'show');
        Route::put('/equipment/{id}',             'update');
        Route::delete('/equipment/{id}',          'destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user',                     'index');
        Route::get('/user/{id}',                'show');
        Route::get('/user-details',              'getUserDetails');
        Route::put('/user/{id}',                'update');
        Route::delete('/user/{id}',             'destroy');
    });

    Route::controller(CitizenServiceController::class)->group(function () {

        Route::delete('/diagnostics/{id}',          'destroy');
        Route::get('/service-availed/{id}',        'show');
    });

    //User Specific APIs
    Route::get('/citizens/availed/{serviceId}', [CitizenServiceController::class, 'getCitizensByService']);
});
