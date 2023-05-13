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
        Schema::create('tenor_imbal_jasas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('imbaljasa_id', false, true);
            $table->integer('tenor');
            $table->bigInteger('imbaljasa', false, true);
            $table->timestamps();
            $table->foreign('imbaljasa_id')->references('id')->on('imbal_jasas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenor_imbal_jasas');
    }
};
