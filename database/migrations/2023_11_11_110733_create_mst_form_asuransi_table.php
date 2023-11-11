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
        Schema::create('mst_form_asuransi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('perusahaan_id', false, true);
            $table->bigInteger('form_item_asuransi_id', false, true);
            $table->timestamps();

            $table->foreign('perusahaan_id')->references('id')->on('mst_perusahaan_asuransi')->cascadeOnDelete();
            $table->foreign('form_item_asuransi_id')->references('id')->on('mst_form_item_asuransi')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_form_asuransi');
    }
};
