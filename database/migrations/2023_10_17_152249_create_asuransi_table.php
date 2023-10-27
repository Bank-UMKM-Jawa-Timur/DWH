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
        Schema::create('asuransi', function (Blueprint $table) {
            $table->string('no_aplikasi', 50)->unique()->comment('Nomor aplikasi/permohonan');
            $table->bigInteger('user_id', false, true);
            $table->string('nama_debitur', 50);
            $table->string('no_polis', 50)->comment('Nomor polis asuransi');
            $table->date('tgl_polis')->comment('Tanggal terbit polis asuransi');
            $table->date('tgl_rekam')->comment('Tanggal insert data');
            $table->enum('status', ['onprogress', 'canceled']);
            $table->date('canceled_at')->nullable();
            $table->bigInteger('canceled_by', false, true)->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('nama_debitur');
            $table->index('no_aplikasi');
            $table->index('no_polis');
            $table->index('tgl_polis');
            $table->index('tgl_rekam');
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
        Schema::dropIfExists('asuransi');
    }
};
