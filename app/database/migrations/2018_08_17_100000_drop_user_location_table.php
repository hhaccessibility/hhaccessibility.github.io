<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropUserLocationTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('user_location');
    }

    public function down()
    {
        Schema::create('user_location', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->foreign('user_id')->references('id')->on('user');
            $table->string('location_id', 36);
            $table->foreign('location_id')->references('id')->on('location');
            $table->double('personalized_rating', 11, 8);
            $table->datetime('when_submitted');
            $table->unique(array('location_id', 'user_id'));
        });
    }
}
