<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstRatePremiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr_val = [
            [
                'masa_asuransi1' => 1,
                'masa_asuransi2' => 12,
                'jenis' => 'bade',
                'rate' => 3.26,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 13,
                'masa_asuransi2' => 24,
                'jenis' => 'bade',
                'rate' => 6.53,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 25,
                'masa_asuransi2' => 36,
                'jenis' => 'bade',
                'rate' => 9.80,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 37,
                'masa_asuransi2' => 48,
                'jenis' => 'bade',
                'rate' => 13.06,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 49,
                'masa_asuransi2' => 60,
                'jenis' => 'bade',
                'rate' => 16.33,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 61,
                'masa_asuransi2' => 72,
                'jenis' => 'bade',
                'rate' => 19.60,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 73,
                'masa_asuransi2' => 84,
                'jenis' => 'bade',
                'rate' => 22.86,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 85,
                'masa_asuransi2' => 96,
                'jenis' => 'bade',
                'rate' => 26.13,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 97,
                'masa_asuransi2' => 108,
                'jenis' => 'bade',
                'rate' => 29.40,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 109,
                'masa_asuransi2' => 120,
                'jenis' => 'bade',
                'rate' => 32.67,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 1,
                'masa_asuransi2' => 12,
                'jenis' => 'plafon',
                'rate' => 3.90,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 13,
                'masa_asuransi2' => 24,
                'jenis' => 'plafon',
                'rate' => 7.80,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 25,
                'masa_asuransi2' => 36,
                'jenis' => 'plafon',
                'rate' => 11.70,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 37,
                'masa_asuransi2' => 48,
                'jenis' => 'plafon',
                'rate' => 15.60,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 49,
                'masa_asuransi2' => 60,
                'jenis' => 'plafon',
                'rate' => 19.50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 61,
                'masa_asuransi2' => 72,
                'jenis' => 'plafon',
                'rate' => 23.40,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 73,
                'masa_asuransi2' => 84,
                'jenis' => 'plafon',
                'rate' => 27.30,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 85,
                'masa_asuransi2' => 96,
                'jenis' => 'plafon',
                'rate' => 31.20,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 97,
                'masa_asuransi2' => 108,
                'jenis' => 'plafon',
                'rate' => 35.10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'masa_asuransi1' => 109,
                'masa_asuransi2' => 120,
                'jenis' => 'plafon',
                'rate' => 39,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        for ($i=0; $i < count($arr_val); $i++) { 
            DB::table('mst_rate_premi')->insert($arr_val[$i]);
        }
    }
}
