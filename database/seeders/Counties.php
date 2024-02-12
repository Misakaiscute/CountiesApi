<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\County;

class Counties extends Seeder
{
    private const COUNTIES = ["Bács-Kiskun",
        "Baranya",
        "Békés",
        "Borsod-Abaúj-Zemplén",
        "Csongrád-Csanád",
        "Fejér",
        "Győr-Moson-Sopron",
        "Hajdú-Bihar",
        "Heves",
        "Jász-Nagykun-Szolnok",
        "Komárom-Esztergom",
        "Nógrád",
        "Pest",
        "Somogy",
        "Szabolcs-Szatmár-Bereg",
        "Tolna",
        "Vas",
        "Veszprém",
        "Zala"];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::COUNTIES as $COUNTY){
            DB::table('counties')->insert([
                'name' => $COUNTY,
                'flag' => $COUNTY . "_flag",
                'coat_of_arms' => $COUNTY . "_coa"
            ]);
        }
    }
}
