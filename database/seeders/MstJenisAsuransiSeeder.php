<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstJenisAsuransiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Kusuma
        DB::table('mst_jenis_asuransi')->insert([
            'jenis_kredit' => 'Kusuma',
            'jenis' => 'Jaminan',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('mst_jenis_asuransi')->insert([
            'jenis_kredit' => 'Kusuma',
            'jenis' => 'Jiwa',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('mst_jenis_asuransi')->insert([
            'jenis_kredit' => 'Kusuma',
            'jenis' => 'Kredit(Penjaminan)',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Talangan Umroh
        DB::table('mst_jenis_asuransi')->insert([
            'jenis_kredit' => 'Talangan Umroh',
            'jenis' => 'Jaminan',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
