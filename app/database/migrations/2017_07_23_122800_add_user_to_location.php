<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
This migration adds a new creator_user_id field to the location table.

This new field should help us keep track of who adds locations.
Knowing who added what locations can be helpful if some users start adding 
bad information such as locations that don't exist or give incorrect 
information on them.
*/
class AddUserToLocation extends Migration
{
    public function up()
    {
        Schema::table('location', function (Blueprint $table) {
			$table->integer('creator_user_id')->unsigned()->nullable();
			$table->foreign('creator_user_id')->references('id')->on('user');
        });
    }

    public function down()
    {
        Schema::table('location', function (Blueprint $table) {
			$table->dropForeign(['creator_user_id']);
            $table->dropColumn('creator_user_id');
        });
    }
}
