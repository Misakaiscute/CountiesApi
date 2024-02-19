<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\County;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class CityController extends Controller
{
    public function getByCountyId($countyId){
        if(!is_numeric($countyId)){
            $idByCountyName = County::select('id')->where('name', '=', $countyId)->first();
            if(!$idByCountyName){
                return response(json_encode([
                    'data' => [],
                    'message' => 'Megye nem található ilyen néven',
                ]), Response::HTTP_NOT_FOUND);
            } else {
                return redirect()->route('CitiesGetByCountyId', ['countyId' => $idByCountyName]);
            }
        }
        $cities = City::where('county_id', '=', $countyId)->get();
        return response(json_encode([
            'data' => $cities,
            'message' => 'Sikeres lekérdezés',
        ]), Response::HTTP_OK);
    }
    public function insert(Request $request){
        County::create([
            'name' => $request->get('name'),
            'flag' => $request->get('name') . "_flag",
            'coat_of_arms' => $request->get('name') . "_coa",
            'population' => $request->get('population'),
            'chief_town' => $request->get('chief_town'),
        ]);

        return response(json_encode([
            'data' => County::where('id', '=', County::count()-1)->get(),
            'message' => 'Megye sikeresen hozzáadva',
        ]), Response::HTTP_CREATED);
    }
    public function delete($id)
    {
        $county = City::find($id);

        if ($county->isEmpty()) {
            return response(json_encode([
                'data' => [],
                'message' => 'Nincs ilyen város',
            ]), Response::HTTP_NOT_FOUND);
        }
        $county->delete();
        return response(json_encode([
            'data' => [],
            'message' => 'Város sikeresen törölve',
        ]), Response::HTTP_OK);
    }
    public function update($countyId, Request $request){
        County::update([
            'county_id' => $countyId,
            'name' => $request->get('name'),
            'postal_code' => $request->get('postal_code'),
        ]);

        return response(json_encode([
            'data' => City::find($request->get('id')),
            'message' => "Város sikeresen frissítve",
        ]),
            Response::HTTP_OK);
    }
}
