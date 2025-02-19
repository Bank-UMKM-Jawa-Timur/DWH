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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 18)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password', 60);
            $table->bigInteger('vendor_id', false, true)->nullable();
            $table->smallInteger('role_id', false, true);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
