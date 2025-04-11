<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(public_path("procedimiento.csv"), "r");
 
        while (($data = fgetcsv($csvFile, null, ",")) !== FALSE) {
            \DB::table('procedures')->insert([
                'min' => $data[0],
                'max' => $data[1],
                'digits' => $data[2],
                'move' => $data[3],
                'divider' => $data[4],
            ]);
        }
        fclose($csvFile);
    }
}
