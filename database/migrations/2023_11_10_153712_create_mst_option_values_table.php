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
        Schema::create('mst_option_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('form_asuransi_id', false, true);
            $table->text('value');
            $table->text('display_value');
            $table->timestamps();

            $table->foreign('form_asuransi_id')
                ->references('id')
                ->on('mst_form_asuransi')
                ->cascadeOnDelete();
            // Create index
            $table->index('value');
            $table->index('display_value');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_option_values');
    }
};
