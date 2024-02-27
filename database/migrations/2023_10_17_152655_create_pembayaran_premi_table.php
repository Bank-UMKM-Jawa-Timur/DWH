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
        Schema::create('pembayaran_premi', function (Blueprint $table) {
            $table->id();
            $table->string('no_aplikasi', 50)->comment('Nomor aplikasi/permohonan');
            $table->string('nobukti_pembayaran', 26);
            $table->date('tgl_bayar');
            $table->decimal('total_premi', 8, 2, true);
            $table->string('no_rek', 26);
            $table->string('no_pk', 30);
            $table->integer('periode_bayar', false, true);
            $table->integer('total_periode', false, true);
            $table->timestamps();

            $table->foreign('no_aplikasi')->references('no_aplikasi')->on('asuransi')->cascadeOnDelete();
            $table->index('nobukti_pembayaran');
            $table->index('tgl_bayar');
            $table->index('total_premi');
            $table->index('no_rek');
            $table->index('periode_bayar');
            $table->index('total_periode');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_premi');
    }
};
