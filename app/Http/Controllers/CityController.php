<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\County;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function getByCountyId($countyId){
        $cities = City::where('county_id', '=', $countyId)->get();
        return response(json_encode([
            'data' => $cities,
            'message' => 'Sikeres lekérdezés',
        ]), Response::HTTP_OK);
    }
}
