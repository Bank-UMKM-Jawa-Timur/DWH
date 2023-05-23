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
        Schema::table('notification_templates', function(Blueprint $table) {
            $table->smallInteger('role_id', false, true)->nullable()->change();
        });
        Schema::table('notifications', function(Blueprint $table) {
            $table->longText('extra')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_templates', function(Blueprint $table) {
            $table->smallInteger('role_id', false, true)->change();
        });
        Schema::table('notifications', function(Blueprint $table) {
            $table->dropColumn('extra');
        });
    }
};
