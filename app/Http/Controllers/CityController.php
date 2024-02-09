<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function all(){
        $cities = City::all();
        return response()->json($cities, Response::HTTP_OK);
    }
    public function insert(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'county_ID' => 'required',
            'postal_code' => 'required'
        ]);
        $city = new City([
            'name' => $validatedData['name'],
            'county_ID' => $validatedData['county_ID'],
            'postal_code' => $validatedData['postal_code']
        ]);
        $city->save();

        return response()->json($city, Response::HTTP_CREATED);
    }
    public function delete($id){
        $city = City::find($id);

        if(!$city){
            return \response()->json(['message' => 'Nincs ilyen város'], Response::HTTP_NOT_FOUND);
        }
        $city->delete();
        return \response()->json(['message' => 'Város sikeresen törölve'], Response::HTTP_OK);
    }
    public function getById($id){
        $city = City::find($id);

        if(!$city){
            return \response()->json(['message' => 'Város nem található'], Response::HTTP_NOT_FOUND);
        }

        return \response()->json($city, Response::HTTP_OK);
    }
    public function update($id, City $updatedCity){
        if(!City::find($id)){
            return \response()->json(['message' => 'Város nem található'], Response::HTTP_NOT_FOUND);
        }

        City::update($updatedCity)->where('id' == $id);
        return \response()->json(['message' => 'Város sikeresen frissítve'], Response::HTTP_OK);
    }
}
