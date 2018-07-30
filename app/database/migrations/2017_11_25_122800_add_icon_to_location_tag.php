<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
This migration adds a new icon_selector field to the location_tag table.
*/
class AddIconToLocationTag extends Migration
{
    public function up()
    {
        Schema::table('location_tag', function (Blueprint $table) {
            $table->string('icon_selector', 255);
        });
    }

    public function down()
    {
        Schema::table('location_tag', function (Blueprint $table) {
            $table->dropColumn('icon_selector');
        });
    }
}
