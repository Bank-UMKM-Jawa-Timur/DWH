<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE asuransi MODIFY COLUMN status ENUM('onprogress', 'canceled', 'done') DEFAULT 'onprogress'");
        Schema::table('asuransi', function(Blueprint $table) {
            $table->date('done_at')->after('is_paid')->nullable();
            $table->bigInteger('done_by')->after('done_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE asuransi MODIFY COLUMN status ENUM('onprogress', 'canceled') DEFAULT 'onprogress'");
        Schema::table('asuransi', function(Blueprint $table) {
            $table->dropColumn('done_at');
            $table->dropColumn('done_by');
        });
    }
};
