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
        Schema::create('pendapat_asuransi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asuransi_id', false, true);
            $table->text('pendapat');
            $table->enum('status', ['process', 'done'])->nullable();
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
        Schema::dropIfExists('pendapat_asuransi');
    }
};
