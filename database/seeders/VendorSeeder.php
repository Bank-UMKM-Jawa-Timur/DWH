<?php

namespace Database\Seeders;

use App\Models\User;
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
        $vendor->cabang_id = 2;
        $vendor->save();

        $vendor = new User();
        $vendor->email = 'bjsc@mail.com';
        $vendor->password = \Hash::make('12345678');
        $vendor->vendor_id = 1;
        $vendor->role_id = 3;
        $vendor->save();
    }
}
