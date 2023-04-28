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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kredit_id', false, true);
            $table->date('date');
            $table->text('file');
            $table->text('text')->nullable();
            $table->integer('document_category_id', false, true);
            $table->timestamps();

            $table->foreign('kredit_id')->references('id')->on('kredits')->cascadeOnDelete();
            $table->foreign('document_category_id')->references('id')->on('document_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
