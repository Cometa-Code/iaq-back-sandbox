<?php

use App\Http\Controllers\CbosController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YoungApprenticesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user', [UserController::class, 'store']);
Route::post('/user/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/user', [UserController::class, 'user']);
    Route::get('/dash_infos', [UserController::class, 'dash_infos']);

    Route::get('/young_apprentices', [YoungApprenticesController::class, 'index']);
    Route::post('/young_apprentices', [YoungApprenticesController::class, 'store']);
    Route::put('/young_apprentices/{id}', [YoungApprenticesController::class, 'update']);
    Route::delete('/young_apprentices/{id}', [YoungApprenticesController::class, 'destroy']);

    Route::get('/companies', [CompaniesController::class, 'index']);
    Route::post('/companies', [CompaniesController::class, 'store']);
    Route::put('/companies/{id}', [CompaniesController::class, 'update']);

    Route::get('/cbos', [CbosController::class, 'index']);
    Route::post('/cbos', [CbosController::class, 'store']);
    Route::put('/cbos/{id}', [CbosController::class, 'update']);

    Route::get('/contracts', [ContractsController::class, 'index']);
    Route::get('/contracts/{id}', [ContractsController::class, 'show']);
    Route::get('/contracts/data/to_contract', [ContractsController::class, 'get_data_to_create_contract']);
    Route::get('/contracts/data/to_make_contract/{apprentice_id}/{company_id}/{cbo_id}', [ContractsController::class, 'get_full_infos_to_make_contract']);
    Route::post('/contracts', [ContractsController::class, 'store']);
    Route::put('/contracts/{id}', [ContractsController::class, 'update']);

});
