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
            $table->text('have_default_value')->change();
            $table->enum('only_accept', ['text', 'alpha', 'alphanumeric', 'numeric'])->after('sequence');
            $table->dropForeign('mst_form_asuransi_perusahaan_id_foreign');
            $table->dropColumn('perusahaan_id');
            $table->string('label')->after('id');
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
            $table->boolean('have_default_value')
                    ->default(false)
                    ->comment('Value from other process, if this field value true = field readonly will be true')
                    ->change();
            $table->bigInteger('perusahaan_id', false, true)->after('id');
            $table->foreign('perusahaan_id')
                    ->references('id')
                    ->on('mst_perusahaan_asuransi')
                    ->cascadeOnDelete();
            $table->dropColumn('label');
        });
    }
};
