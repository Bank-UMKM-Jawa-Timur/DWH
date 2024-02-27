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
        Schema::create('pengajuan_klaim', function (Blueprint $table) {
            $table->id();
            $table->string('no_klaim', 50);
            $table->string('no_aplikasi', 50);
            $table->string('no_rek', 50);
            $table->enum('stat_klaim', [1,2,3,4,5,6,7])->comment('Status Klaim 1=sedang di proses 2=disetujui dan sedang menunggu pembayaran 3=disetujui dan telah dibayarkan 4 =dokumen belum lengkap 5 = Premi Belum Dibayar 6 = Ditolak 7 = data tidak ditemukan');
            $table->enum('status', ['onprogress', 'canceled']);
            $table->date('canceled_at')->nullable();
            $table->bigInteger('canceled_by', false, true)->nullable();
            $table->timestamps();

            $table->foreign('no_aplikasi')->references('no_aplikasi')->on('asuransi')->cascadeOnDelete();
            $table->index('no_klaim');
            $table->index('no_rek');
            $table->index('stat_klaim');
            $table->index('created_at');
            $table->index('canceled_at');
            $table->index('canceled_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_klaim');
    }
};
