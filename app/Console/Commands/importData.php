<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\County;
use Illuminate\Console\Command;

class importData extends Command
{
    protected $signature = 'app:import-data {filename=zip_codes.csv : Name of the file containing the data}';
    protected $description = 'Input data into the database';

    public function handle() : void
    {
        $filename = $this->argument('filename');
        if(!file_exists(resource_path($filename))){
            $this->error("The file does not exist.");
        }

        $bar = $this->output->createProgressBar();
        $bar->setFormat("%current_step%\n[%bar%] %elapsed%");
        $bar->setEmptyBarCharacter('░');
        $bar->setBarCharacter('▓');
        $bar->setProgressCharacter('▓');

        $data = $this->readCSV(resource_path($filename), $bar);
        $this->putIntoDB($data, $bar);

        $this->newLine(); $this->info("Data successfully imported");
    }
    public function readCSV($csvFile, $progressBar) : array
    {
        $progressBar->start();

        $line_of_text = array();
        $file_handle = fopen($csvFile, 'r');
        while ($csvRow = fgetcsv($file_handle, null, ',')) {
            $line_of_text[] = $csvRow;
            $progressBar->setMessage("Reading file", 'current_step');
            $progressBar->advance();
        }
        fclose($file_handle);
        $progressBar->finish();
        return $line_of_text;
    }
    private function putIntoDB(array $dataArray, $progressBar) : void{
        $progressBar->start();
        $progressBar->setMessage("Intialization");

        $idCounty = array_search("county", $dataArray[0]);
        $idZipcode = array_search("zip_code", $dataArray[0]);
        $idCity = array_search("city", $dataArray[0]);
        array_splice($dataArray, 0, 1);

        $countyId_TO_NameArray = [];
        for($i = 0; $i < count($dataArray); $i++){
            $countyName = $dataArray[$i][$idCounty];
            $countyCity = $dataArray[$i][$idCity];
            $cityZip = $dataArray[$i][$idZipcode];

            if(empty($countyName)){
                $countyName = "Special district Budapest";
            }
            if(in_array($countyName, $countyId_TO_NameArray)){
                $countyId = array_search($countyName, $countyId_TO_NameArray);
                $this->addToCityDB($cityZip, $countyCity, $countyId);
                $progressBar->setMessage("Appending data for county ".$countyName, 'current_step');
                $progressBar->advance();
            } else {
                $countyId_TO_NameArray[] = $countyName;
                $countyId = array_search($countyName, $countyId_TO_NameArray);
                $this->addToCityDB($cityZip, $countyCity, $countyId);
                $progressBar->setMessage("Appending data for county ".$countyName, 'current_step');
                $progressBar->advance();
            }
        }
        for($i = 0; $i < count($countyId_TO_NameArray); $i++){
            $name = $countyId_TO_NameArray[$i];
            County::create([
                'name' => $name,
                'flag' => $name . "_flag",
                'coat_of_arms' => $name . "_coa",
            ]);
            $progressBar->setMessage("Appending counties", 'current_step');
            $progressBar->advance();
        }
        $progressBar->finish();
    }
    private function addToCityDB($zipcode, $name, $countyId) : void{
        City::create([
            'county_id' => $countyId + 1,
            'name' => $name,
            'postal_code' => $zipcode,
        ]);
    }
}

