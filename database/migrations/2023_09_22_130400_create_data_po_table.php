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
        Schema::create('data_po', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('imported_data_id', false, true);
            $table->string('merk', 50);
            $table->string('tipe', 50);
            $table->string('tahun_kendaraan', 4)->nullable();
            $table->string('warna', 25)->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('jumlah', false, true);
            $table->bigInteger('harga', false, true);
            $table->timestamps();

            $table->foreign('imported_data_id')->references('id')->on('imported_data')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_po');
    }
};
