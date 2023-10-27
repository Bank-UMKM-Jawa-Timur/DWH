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
        Schema::table('pembayaran_premi', function(Blueprint $table) {
            $table->decimal('total_premi', 10, 2, true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_premi', function(Blueprint $table) {
            $table->decimal('total_premi', 8, 2, true)->change();
        });
    }
};
