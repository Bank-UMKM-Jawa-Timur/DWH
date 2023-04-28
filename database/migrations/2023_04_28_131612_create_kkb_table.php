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
        Schema::create('kkb', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kredit_id', false, true);
            $table->date('tgl_ketersedian_unit');
            $table->string('imbal_jasa', 12);
            $table->timestamps();

            $table->foreign('kredit_id')->references('id')->on('kredits')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kkb');
    }
};
