<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function all($countyId){
        return response(json_encode([
           'data' => City::where('county')
        ]), Response::HTTP_OK);
    }
}
