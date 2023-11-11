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
        $from = 'mst_form_asuransi';
        $to = 'mst_form_item_asuransi';
        Schema::rename($from, $to);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $from = 'mst_form_item_asuransi';
        $to = 'mst_form_asuransi';
        Schema::rename($from, $to);
    }
};
