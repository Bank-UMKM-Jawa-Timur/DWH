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
        Schema::create('mst_jenis_asuransi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('produk_kredit_id', false, true)->nullable();
            $table->string('jenis_kredit', 26)->nullable();
            $table->string('jenis', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_jenis_asuransi');
    }
};
