<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE asuransi_detail MODIFY COLUMN tipe_premi ENUM('0', '1') COMMENT '0 = biasa | 1 = refund'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE asuransi_detail MODIFY COLUMN tipe_premi ENUM('1', '2') COMMENT '1 = biasa | 02 = refund'");
    }
};
