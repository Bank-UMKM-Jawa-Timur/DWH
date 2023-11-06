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
        Schema::create('pengajuan_klaim_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pengajuan_klaim_id', false, true);
            $table->string('no_sp3')->nullable();
            $table->date('tgl_sp3')->nullable();
            $table->integer('tunggakan_pokok')->nullable();
            $table->integer('tunggakan_bunga')->nullable();
            $table->integer('tunggakan_denda')->nullable();
            $table->integer('nilai_pengikatan')->nullable();
            $table->integer('nilai_tuntutan_klaim')->nullable();
            $table->string('penyebab_klaim')->nullable();
            $table->string('kode_agunan')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_klaim_id')->references('id')->on('pengajuan_klaim')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_klaim_detail');
    }
};
