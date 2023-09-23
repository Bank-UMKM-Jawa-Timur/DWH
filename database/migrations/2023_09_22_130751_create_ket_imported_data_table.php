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
        Schema::create('ket_imported_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('imported_data_id', false, true);
            $table->text('keterangan');
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
        Schema::dropIfExists('ket_imported_data');
    }
};
