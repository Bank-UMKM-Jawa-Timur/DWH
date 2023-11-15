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
        Schema::create('form_value_asuransi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asuransi_id', false, true);
            $table->bigInteger('form_item_asuransi_id', false, true);
            $table->text('value');
            $table->timestamps();

            $table->foreign('asuransi_id')
                ->references('id')
                ->on('asuransi')
                ->cascadeOnDelete();
            $table->foreign('form_item_asuransi_id')
                ->references('id')
                ->on('mst_form_item_asuransi')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_value_asuransi');
    }
};
