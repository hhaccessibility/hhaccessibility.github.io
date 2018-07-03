<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * This migration adds suggestion table to store suggestions of information of locations 
 * generated by users.
 */

class CreateSuggestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggestion',function(Blueprint $table){
            $table->increments('id');
            $table->string('user_id',36);
            $table->foreign('user_id')->references('id')->on('user');
            $table->string('location_id',36);
            $table->foreign('location_id')->references('id')->on('location');
            $table->string('location_name', 255)->nullable();
            $table->string('location_external_web_url', 255)->nullable();
            $table->string('location_address', 255)->nullable();
            $table->string('location_phone_number', 50)->nullable();
            $table->timestamp('when_generated');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suggestion');
    }
}
