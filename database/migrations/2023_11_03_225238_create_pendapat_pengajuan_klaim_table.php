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
        Schema::create('pendapat_pengajuan_klaim', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pengajuan_klaim_id', false, true);
            $table->text('pendapat');
            $table->enum('status', ['process', 'done'])->nullable();
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
        Schema::dropIfExists('pendapat_pengajuan_klaim');
    }
};
