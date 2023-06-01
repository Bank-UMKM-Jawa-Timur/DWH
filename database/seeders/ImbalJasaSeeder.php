<?php

namespace Database\Seeders;

use App\Models\ImbalJasa;
use App\Models\TenorImbalJasa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImbalJasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new ImbalJasa();
        $model->plafond1 = 0;
        $model->plafond2 = 10000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 10000000;
        $model->plafond2 = 15000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 15000000;
        $model->plafond2 = 20000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 20000000;
        $model->plafond2 = 25000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 25000000;
        $model->plafond2 = 30000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 30000000;
        $model->plafond2 = 35000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 35000000;
        $model->plafond2 = 40000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 40000000;
        $model->plafond2 = 45000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 45000000;
        $model->plafond2 = 50000000;
        $model->save();

        $model = new ImbalJasa();
        $model->plafond1 = 50000000;
        $model->plafond2 = 0;
        $model->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 1;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 100000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 1;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 200000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 1;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 300000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 2;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 150000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 2;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 300000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 2;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 400000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 3;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 200000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 3;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 400000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 3;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 550000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 4;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 250000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 4;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 450000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 4;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 700000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 5;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 300000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 5;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 550000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 5;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 800000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 6;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 350000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 6;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 650000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 6;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 950000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 7;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 400000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 7;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 750000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 7;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 1050000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 8;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 400000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 8;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 800000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 8;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 1300000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 9;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 450000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 9;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 900000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 9;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 1350000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 10;
        $modelDetail->tenor = 12;
        $modelDetail->imbaljasa = 500000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 10;
        $modelDetail->tenor = 24;
        $modelDetail->imbaljasa = 1000000;
        $modelDetail->save();

        $modelDetail = new TenorImbalJasa();
        $modelDetail->imbaljasa_id = 10;
        $modelDetail->tenor = 36;
        $modelDetail->imbaljasa = 1500000;
        $modelDetail->save();
    }
}
