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
        Schema::create('mst_file_content_dictionary', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('file_dictionary_id', false, true);
            $table->string('field', 50);
            $table->smallInteger('from', false, true);
            $table->smallInteger('to', false, true);
            $table->tinyInteger('length', false, true);
            $table->tinyText('description');
            $table->timestamps();

            $table->foreign('file_dictionary_id')->references('id')->on('mst_file_dictionary')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_file_content_dictionary');
    }
};
