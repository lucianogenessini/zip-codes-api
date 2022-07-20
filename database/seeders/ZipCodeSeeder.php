<?php

namespace Database\Seeders;

use App\Models\FederalEntity;
use App\Models\Municipality;
use App\Models\ZipCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZipCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set("memory_limit", "-1");

        $xmlString = file_get_contents(storage_path('data_seeder/CPdescarga.xml'));
        $xmlObject = @simplexml_load_string($xmlString);
                   
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true); 

        foreach ($phpArray as $item){
            foreach ($item as $key=>$value){
                $municipality = Municipality::where('key', $value['c_mnpio'])->where('name', $value['D_mnpio'])->first();
                $federal_entity = FederalEntity::where('key', $value['c_estado'])->first();

                ZipCode::firstOrCreate([
                    'zip_code' => $value['d_codigo'],
                    'locality' => isset($value['d_ciudad']) ? mb_strtoupper($value['d_ciudad']) : "",
                    'municipality_id' => $municipality->id,
                    'federal_entity_id' => $federal_entity->id
                ]);
            }
        }

        unset($phpArray);
        unset($xmlString);
        unset($xmlObject);
    }
}
