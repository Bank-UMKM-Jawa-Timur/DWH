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
        
        $pemasaran = new User();
        $pemasaran->nip = 123456789012345676;
        $pemasaran->password = \Hash::make('12345678');
        $pemasaran->role_id = 2;
        $pemasaran->save();

        $cabang = new User();
        $cabang->nip = 123456789012345675;
        $cabang->password = \Hash::make('12345678');
        $cabang->role_id = 4; // superadmin
        $cabang->save();
    }
}
