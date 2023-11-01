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
        Schema::create('asuransi_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asuransi_id', false, true);
            $table->enum('kolektibilitas', [1, 2, 3, 4, 5]);
            $table->enum('jenis_pertanggungan', ['01', '02'])->comment('01 = pokok | 02 = sisa kredit');
            $table->enum('tipe_premi', [1, 2])->comment('1 = biasa | 02 = refund');
            $table->enum('jenis_coverage', ['01', '02', '03', '04', '05', '06'])
                ->comment('01 = PNS & NON PNS (PA+ND) | 02 = NON PNS (PA+ND+PHK) | 03 = PNS (PA+ND+PHK+MACET) | 04 = DPRD (PA+ND+PAW) | 05 = PNS & PENSIUN (PA+ND) | 06 = DPRD (PA+ND+PAW)');
            $table->string('no_polis_sebelumnya', 50)->nullable();
            $table->decimal('baki_debet', 10, 2, true)->nullable();
            $table->decimal('tunggakan', 10, 2, true)->nullable();
            $table->decimal('tarif', 10, 2, true);
            $table->enum('kode_layanan_syariah', ['0', '1'])->comment('0 = KV | 1 = SY');
            $table->decimal('handling_fee', 10, 2, true);
            $table->decimal('premi_disetor', 10, 2, true);
            $table->timestamps();

            $table->foreign('asuransi_id')->references('id')->on('asuransi')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asuransi_detail');
    }
};
