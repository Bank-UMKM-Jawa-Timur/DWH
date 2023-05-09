<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newDoc = new DocumentCategory();
        $newDoc->name = 'STNK';
        $newDoc->save();

        $newDoc = new DocumentCategory();
        $newDoc->name = 'Police';
        $newDoc->save();

        $newDoc = new DocumentCategory();
        $newDoc->name = 'BPKB';
        $newDoc->save();
    }
}
