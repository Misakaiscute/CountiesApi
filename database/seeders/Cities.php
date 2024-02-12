<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\County;
use Illuminate\Database\Seeder;

class Cities extends Seeder
{
    private function generateRandomZipCode() : int{
        return mt_rand(1000, 9999);
    }

    private const ZALA_CITIES = [
        "Zalaegerszeg",
        "Nagykanizsa",
        "Keszthely",
        "Lenti",
        "Letenye",
        "Zalaszentgrót",
        "Csáktornya",
        "Zalakaros",
        "Pacsa",
        "Göcsej"];
    public function run(): void
    {
        $result = County::select('id')->where('name', '=', 'Zala')->first();

        foreach (self::ZALA_CITIES as $CITY){
            City::insert([
                'county_id' => $result['id'],
                'name' => $CITY,
                'postal_code' => $this->generateRandomZipCode(),
            ]);
        }
    }
}
