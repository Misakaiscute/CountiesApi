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
Route::get('counties', [CountyController::class, 'all'])->name("counties");
Route::get('counties/{id}', [CountyController::class, 'getById'])->name("countyGet");
Route::post('counties/add', [CountyController::class, 'insert'])->name("countyAdd");
Route::delete('counties/{id}', [CountyController::class, 'delete'])->name("countyDelete");
Route::patch('counties/{id}', [CountyController::class, 'update'])->name("countyUpdate");

Route::get('counties/{countyId}/cities', [CityController::class, 'getByCountyId'])->name("citiesGetByCountyId");
Route::post('counties/{countyId}/cities/add', [CityController::class, 'insert'])->name("cityAdd");
Route::delete('counties/{countyId}/cities/{cityId}', [CityController::class, 'delete'])->name("cityDelete");
Route::patch('counties/{countyId}/cities/{cityId}', [CityController::class, 'update'])->name("cityUpdate");


