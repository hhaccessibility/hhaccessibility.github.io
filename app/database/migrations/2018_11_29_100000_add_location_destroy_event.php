<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationDestroyEvent extends Migration
{
    public function up()
    {
        Schema::create('location_event', function (Blueprint $table) {
            $table->string('id', 36)->nullable(false);
            $table->primary(['id']);
            $table->string('external_web_url', 255)->nullable(true);
            $table->string('name', 255)->nullable(false);
            $table->string('description', 255)->nullable(false);
            $table->date('when')->nullable(false);
        });
        Schema::table('location', function (Blueprint $table) {
            $table->string('destroy_location_event_id', 36)->nullable(true);
            $table->foreign('destroy_location_event_id')->references('id')->on('location_event');
        });
    }

    public function down()
    {
        Schema::table('location', function (Blueprint $table) {
            $table->dropForeign(['destroy_location_event_id']);
            $table->dropColumn('destroy_location_event_id');
        });
        Schema::dropIfExists('location_event');
    }
}
