<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\County;
class CountyController extends Controller
{
    static function databaseEmpty()
    {
        if (County::count() == 0) {
            return true;
        }
        return false;
    }
    private function readCsv(Request $request) : array{
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
    static function populateDatabase(Request $request) : void{
        $file = $request->file('file');
        $data = file($file->getContent());
        dd($data);

        $header = $data[0];
        $county = array_search('county', $header);
        $zipCode = array_search('zip_code', $header);
        $city = array_search('city', $header);

        $isHeader = true;
        $counties = []; //tömb: megye id előfordulási sorrendben

        foreach ($data as $oneData){
            if ($isHeader){
                $isHeader = false;
                continue;
            }
            if ($oneData[$county] == "") {
                $oneData[$county] = "Pest";
            }
            if (!in_array($oneData[$county], $counties)){
                $counties[] = $oneData[$county];
            }
            $cities = [array_search($oneData[$county], $counties)+1, $oneData[$city], $oneData[$zipCode]];
            self::populateCityTable($cities);
            unset($cities);
        }
        self::populateCountyTable($counties);
    }
    private static function populateCityTable($cities) : void{
        County::table('cities')->insert([
            'county_ID' => $cities[0],
            'name' => $cities[1],
            'zip_code' => $cities[2],
        ]);
    }
    private static function populateCountyTable($counties) : void{
        foreach($counties as $county){
            County::table('counties')->insert([
                'name' => $county,
                'flag' => $county . '.svg',
                'coat_of_arms' => $county . '_coa.svg',
                'chief_town' => null,
                'population' => null,
            ]);
        }
    }
}
