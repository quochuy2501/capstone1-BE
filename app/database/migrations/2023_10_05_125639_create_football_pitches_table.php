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
        Schema::create('football_pitches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('image');
            $table->double('price');
            $table->longText('detailed_schedule');
            $table->longText('describe');
            $table->integer('id_owner')->nullable;
            $table->integer('id_category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('football_pitches');
    }
};
