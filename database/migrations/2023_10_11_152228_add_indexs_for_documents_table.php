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
        Schema::table('documents', function(Blueprint $table) {
            $table->index('date');
            $table->index('is_imported_data');
            $table->index('is_confirm');
            $table->index('confirm_at');
            $table->index('confirm_by');
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
        Schema::table('documents', function(Blueprint $table) {
            $table->dropIndex('documents_date_index');
            $table->dropIndex('documents_is_imported_data_index');
            $table->dropIndex('documents_is_confirm_index');
            $table->dropIndex('documents_confirm_at_index');
            $table->dropIndex('documents_confirm_by_index');
            $table->dropIndex('documents_created_at_index');
            $table->dropIndex('documents_updated_at_index');
        });
    }
};
