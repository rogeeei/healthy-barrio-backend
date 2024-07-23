<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\CitizenDetailsController;
use App\Http\Controllers\CitizenHistoryController;


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


//Private API
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    //Admin APIs
    Route::patch('/user/{user}/approve', [UserController::class, 'approve'])->name('user.approve');

    Route::controller(CitizenDetailsController::class)->group(function () {
        Route::get('/citizen', 'index');
        Route::post('/citizen', 'store');
        Route::delete('/citizen/{id}', 'destroy');
        Route::put('/citizen/{id}', 'update')->name('citizen.update');
    });

    Route::controller(CitizenHistoryController::class)->group(function () {
        Route::get('/citizen-history',           'index');
        Route::get('/citizen-history/{id}',      'show');
        Route::post('/citizen-history',          'store');
    });

    Route::controller(DiagnosticController::class)->group(function () {
        Route::post('/diagnostics',               'store');
        Route::delete('/diagnostics/{id}',          'destroy');
    });

    Route::controller(MedicineController::class)->group(function () {
        Route::post('/medicine',                 'store');
        Route::delete('/medicine/{id}',          'destroy');
        Route::get('/medicine',                  'index');
    });

    Route::controller(EquipmentController::class)->group(function () {
        Route::get('/equipment',                   'index');
        Route::post('/equipment',                 'store');
        Route::delete('/equipment/{id}',          'destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user',                     'index');
        Route::get('/user/{id}',                'show');
        Route::put('/user/{id}',                'update')->name('user.update');
        Route::put('/user/email/{id}',          'email')->name('user.email');
        Route::put('/user/password/{id}',       'password')->name('user.password');
        Route::put('/user/image/{id}',          'image')->name('user.image');
        Route::delete('/user/{id}',             'destroy');
    });
    //User Specific APIs

});
