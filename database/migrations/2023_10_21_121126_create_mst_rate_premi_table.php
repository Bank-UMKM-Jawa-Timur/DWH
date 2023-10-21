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
        Schema::create('mst_rate_premi', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('masa_asuransi', false, true)->comment('bulan');
            $table->enum('jenis', ['bade', 'plafon']);
            $table->decimal('rate', 5, 2, true);
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
        Schema::dropIfExists('mst_rate_premi');
    }
};
