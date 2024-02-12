<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
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
Route::get('counties/{id}', [CountyController::class, 'getById'])->name("CountyGetById");
Route::get('counties/{name}', [CountyController::class, 'getByName'])->name("CountyGetByName");
Route::post('counties/add', [CountyController::class, 'insert'])->name("CountyAdd");
Route::delete('counties/{id}', [CountyController::class, 'delete'])->name("CountyDelete");

