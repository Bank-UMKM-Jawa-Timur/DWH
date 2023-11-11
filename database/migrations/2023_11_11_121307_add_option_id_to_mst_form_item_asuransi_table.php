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
        Schema::table('mst_form_item_asuransi', function (Blueprint $table) {
            $table->bigInteger('option_id', false, true)->nullable()->after('id');
            $table->foreign('option_id')
                ->references('id')
                ->on('mst_option_values')
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
        Schema::table('mst_form_item_asuransi', function (Blueprint $table) {
            $table->dropForeign('mst_form_item_asuransi_option_id_foreign');
            $table->dropColumn('option_id');
        });
    }
};
