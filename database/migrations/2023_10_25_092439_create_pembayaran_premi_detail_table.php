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
        Schema::create('pembayaran_premi_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pembayaran_premi_id', false, true);
            $table->string('no_rek', 26);
            $table->string('no_pk', 30);
            $table->integer('periode_bayar', false, true);
            $table->integer('total_periode', false, true);
            $table->timestamps();
            
            $table->index('no_rek');
            $table->index('periode_bayar');
            $table->index('total_periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_premi_detail');
    }
};
