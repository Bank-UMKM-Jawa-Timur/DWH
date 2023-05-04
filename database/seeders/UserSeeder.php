<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pemasaran = new User();
        $pemasaran->nip = 123456789012345678;
        $pemasaran->password = \Hash::make('12345678');
        $pemasaran->role_id = 1;
        $pemasaran->save();

        $cabang = new User();
        $cabang->nip = 123456789012345677;
        $cabang->password = \Hash::make('12345678');
        $cabang->role_id = 2;
        $cabang->save();

        $vendor = new User();
        $vendor->email = 'bjsc@mail.com';
        $vendor->password = \Hash::make('12345678');
        $vendor->vendor_id = 1;
        $vendor->role_id = 3;
        $vendor->save();
    }
}
