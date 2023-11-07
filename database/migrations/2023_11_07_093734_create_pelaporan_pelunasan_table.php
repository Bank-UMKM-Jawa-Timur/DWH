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
        Schema::create('pelaporan_pelunasan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asuransi_id', false, true);
            $table->date('tanggal');
            $table->decimal('refund', 10, 2, true);
            $table->smallInteger('sisa_jkw', false, true);
            $table->bigInteger('user_id', false, true);
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
        Schema::dropIfExists('pelaporan_pelunasan');
    }
};
