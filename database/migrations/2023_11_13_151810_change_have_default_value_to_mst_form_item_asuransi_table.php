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
            $table->renameColumn('have_default_value', 'default_value');
            $table->text('have_default_value')
                ->nullable()
                ->comment('Value from other process, if this field value true = field readonly will be true')
                ->change();
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
            $table->renameColumn('default_value', 'have_default_value');
            $table->text('default_value')
                ->nullable()
                ->comment('Value from other process, if this field value true = field readonly will be true')
                ->change();
        });
    }
};
