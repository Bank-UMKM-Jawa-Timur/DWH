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
        Schema::table('asuransi', function (Blueprint $table) {
            $table->bigInteger('perusahaan_asuransi_id', false, true)->nullable()->change();
            $table->string('no_aplikasi', 50)->unique()->nullable()->comment('Nomor aplikasi/permohonan')->change();
            $table->string('no_pk', 100)->nullable()->change();
            $table->string('no_rek', 100)->nullable()->change();
            $table->decimal('premi', 10, 2, true)->nullable()->change();
            $table->string('no_polis', 50)->nullable()->comment('Nomor polis asuransi')->change();
            $table->date('tgl_polis')->nullable()->comment('Tanggal terbit polis asuransi')->change();
            $table->date('tgl_rekam')->nullable()->comment('Tanggal insert data')->change();
            $table->date('tanggal_awal')->nullable()->change();
            $table->date('tanggal_akhir')->nullable()->change();
            $table->boolean('is_paid')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asuransi', function (Blueprint $table) {
            $table->bigInteger('perusahaan_asuransi_id', false, true)->nullable(false)->change();
            $table->string('no_aplikasi', 50)->unique()->nullable(false)->comment('Nomor aplikasi/permohonan')->change();
            $table->string('no_pk', 100)->nullable(false)->change();
            $table->string('no_rek', 100)->nullable(false)->change();
            $table->decimal('premi', 10, 2, true)->nullable(false)->change();
            $table->string('no_polis', 50)->nullable(false)->comment('Nomor polis asuransi')->change();
            $table->date('tgl_polis')->nullable(false)->comment('Tanggal terbit polis asuransi')->change();
            $table->date('tgl_rekam')->nullable(false)->comment('Tanggal insert data')->change();
            $table->date('tanggal_awal')->nullable(false)->change();
            $table->date('tanggal_akhir')->nullable(false)->change();
            $table->boolean('is_paid')->nullable(false)->change();
        });
    }
};
