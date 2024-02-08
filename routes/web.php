<?php

use Illuminate\Support\Facades\Route;
use App\Models\County;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/import', function () {
    return view('emptyDatabase');
});

Route::post("/import-file", 'CountyController@populateDatabase');

Route::get('/', function () {
    return view('Counties');
});

Route::get('/{id}', function ($id){
    return view('oneCounty', [
         'county' => County::findOrFail($id)
        ]);
});
