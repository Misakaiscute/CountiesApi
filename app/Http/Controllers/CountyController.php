<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\County;
class CountyController extends Controller
{
    function retrieve(){
        $counties = County::orderBy('name')->get();
        $content = json_encode([$counties]);
        return response($content, Response::HTTP_OK);
    }
    function readCsv($fileName){
        if (!file_exists($fileName)) {
            echo "$fileName nem található";
            return false;
        }
        $csvFile = fopen($fileName, 'r');
        $lines = [];
        while (!feof($csvFile)) {
            $line = fgetcsv($csvFile);
            $lines[] = $line;
        }
        fclose($csvFile);

        return $lines;
    }
    function populateDatabase(){
        $data = $this->readCsv('zip_codes.csv');

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
            $this->populateCityTable($cities);
            unset($cities);
        }
    }
    function populateCityTable($cities){
        County::table('cities')->insert([
            'county_ID' => $cities[0],
            'city_name' => $cities[1],
            'zip_code' => $cities[2],
        ]);
    }
    function populateCountyTable($counties){
        foreach($counties as $county){
            County::table('county')->insert([

            ]);
        }
    }
}
