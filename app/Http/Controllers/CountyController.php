<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function all(){
        return response(json_encode([
            'data' => County::get(),
            'message' => "Sikeres lekérés",
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
    public function delete($id){
        $county = County::find($id);

        if($county->isEmpty()){
            return response(json_encode([
                'data' => [],
                'message' => 'Nincs ilyen megye',
            ]), Response::HTTP_NOT_FOUND);
        }
        $county->delete();
        return response(json_encode([
            'data' => [],
            'message' => 'Megye sikeresen törölve',
        ]), Response::HTTP_OK);
    }
    public function getById($id){
        if(!is_numeric($id)){
            $idByCountyName = County::select('id')->where('name', '=', $id)->first();
            if($idByCountyName->isEmpty()){
                return redirect()->route('Counties');
            } else {
                return redirect()->route('CountyGetById', ['id' => $idByCountyName]);
            }
        }
            $county = County::find($id);
            if(!$county->first()){
                return response(json_encode([
                    'data' => [],
                    'message' => 'Megye nem található ilyen id alatt',
                ]), Response::HTTP_NOT_FOUND);
            }
            return response(json_encode([
                'data' => $county,
                'message' => 'Sikeres lekérdezés',
                ]), Response::HTTP_OK);
    }
    public function update(Request $request){
        County::update([
            'name' => $request->get('name'),
            'flag' => $request->get('name') . "_flag",
            'coat_of_arms' => $request->get('name') . "_coa",
            'population' => $request->get('population'),
            'chief_town' => $request->get('chief_town'),
        ]);

        return response(json_encode([
            'data' => County::find($request->get('id')),
            'message' => "Megye sikeresen frissítve",
        ]),
            Response::HTTP_OK);
    }
}
