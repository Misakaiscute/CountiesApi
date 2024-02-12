<?php

namespace App\Http\Controllers;

use App\Models\County;
use http\Env\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CountyController extends Controller
{
    private static function readCsv($file): array
    {
        $lines = array();
        if (file_exists($file)) {
            $csvFile = fopen($file, 'r');
            while (!feof($csvFile)) {
                $line = fgetcsv($csvFile);
                $lines[] = $line;
            }
            fclose($csvFile);
        }
        return $lines;
    }
    private static function populateDatabase($file): void
    {
        $data = self::readCsv('..\..\..\zip_codes.csv');

        $header = $data[0];
        $county = array_search('county', $header);
        $zipCode = array_search('zip_code', $header);
        $city = array_search('city', $header);

        $isHeader = true;
        $counties = []; //tömb: megye id előfordulási sorrendben

        foreach ($data as $oneData) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }
            if (!in_array($oneData[$county], $counties)) {
                $counties[] = $oneData[$county];
            }
            $cities = [array_search($oneData[$county], $counties) + 1, $oneData[$city], $oneData[$zipCode]];
            self::populateCityTable($cities);
            unset($cities);
        }
        self::populateCountyTable($counties);
    }
    private static function populateCityTable($cities): void
    {
        DB::table('counties')->insert([
            'county_ID' => $cities[0],
            'name' => $cities[1],
            'zip_code' => $cities[2],
        ]);
    }
    private static function populateCountyTable($counties): void
    {
        foreach ($counties as $county) {
            County::table('counties')->insert([
                'name' => $county,
                'flag' => $county . '.svg',
                'coat_of_arms' => $county . '_coa.svg',
                'chief_town' => null,
                'population' => null,
            ]);
        }
    }
    public function all(){
        $counties = County::get();
        $content = json_encode($counties);
        return response($content, Response::HTTP_OK);
    }
    public function insert(Request $request){
        $validatedData = $request->validate([
            'name' => 'required'
        ]);
        $county = new County([
            'name' => $validatedData['name'],
            'flag' => $validatedData['name'] . "_flag",
            'coat_of_arms' => $validatedData['name'] . "_coa"
        ]);
        $county->save();

        return response()->json($county, Response::HTTP_CREATED);
    }
    public function delete($id){
        $county = County::find($id);

        if(!$county){
            return response()->json(['message' => 'Nincs ilyen megye'], Response::HTTP_NOT_FOUND);
        }
        $county->delete();
        return response()->json(['message' => 'Megye sikeresen törölve'], Response::HTTP_OK);
    }
    public function getById(Request $request){
        $id = $request->getRequestUrl();
        ddd($id);

        $county = County::find($id);

        if(!$county){
            return response(['message' => 'Megye nem található'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($county, Response::HTTP_OK);
    }
    public function update($id, County $updatedCounty){
        if(!County::find($id)){
            return response()->json(['message' => 'Megye nem található'], Response::HTTP_NOT_FOUND);
        }

        County::update($updatedCounty)->where('id' == $id);
        return response()->json(['message' => 'Megye sikeresen frissítve'], Response::HTTP_OK);
    }
}
