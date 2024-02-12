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
Route::get('counties', [CountyController::class, 'all'])->name("CountyShow");
Route::get('counties/{slug}', [CountyController::class, 'getBySlug'])->name("CountyGetByIdOrName");
Route::post('counties/add', [CountyController::class, 'insert'])->name("CountyAdd");
Route::delete('counties/{id}', [CountyController::class, 'delete'])->name("CountyDelete");
Route::patch('counties/{id}', [CountyController::class, 'update'])->name("CountyUpdate");

Route::get('counties/{countySlug}/city/{citySlug}', [CityController::class, 'getBySlug'])->name("CityGetByIdOrName");

