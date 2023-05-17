<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pemasaran = new Role();
        $pemasaran->name = 'Pemasaran';
        $pemasaran->save();

        $pemasaran = new Role();
        $pemasaran->name = 'Cabang';
        $pemasaran->save();

        $pemasaran = new Role();
        $pemasaran->name = 'Vendor';
        $pemasaran->save();

        $pemasaran = new Role();
        $pemasaran->name = 'Superadmin';
        $pemasaran->save();
    }
}
