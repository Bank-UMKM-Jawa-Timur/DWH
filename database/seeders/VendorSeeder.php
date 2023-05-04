<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendor = new Vendor();
        $vendor->name = 'BJSC';
        $vendor->address = 'Mojokerto';
        $vendor->phone = '08xxxxxxxxxx';
        $vendor->save();
    }
}
