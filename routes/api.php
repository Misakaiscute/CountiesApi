<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\CityController;
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
Route::get('/counties', [CountyController::class, 'all'])->name("Counties");
Route::get('counties/{id}', [CountyController::class, 'getById'])->name("CountyGetById");
Route::post('counties/add', [CountyController::class, 'insert'])->name("CountyAdd");
Route::delete('counties/{id}', [CountyController::class, 'delete'])->name("CountyDelete");
Route::patch('counties/{id}', [CountyController::class, 'update'])->name("CountyUpdate");

Route::get('counties/{countyId}/cities}', [CityController::class, 'getByCountyId'])->name("CitiesGetByCountyId");
Route::post('counnties/{countyId}/cities/add', [CityController::class, 'insert'])->name("CityAdd");
Route::delete('counties/{countyId}/cities/{cityId}', [CityController::class, 'delete'])->name("CityDelete");
Route::patch('counties/{countyId}/cities/{cityId}', [CityController::class, 'update'])->name("CityUpdate");


