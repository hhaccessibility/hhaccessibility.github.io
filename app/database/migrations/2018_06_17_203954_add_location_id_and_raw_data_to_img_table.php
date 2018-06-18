<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationIdAndRawDataToImgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image', function (Blueprint $table) {
            $table->string('location_id', 36)->nullable(false);
            $table->foreign('location_id')->references('id')->on('location');
            $table->binary('raw_data', 16777215);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image', function (Blueprint $table) {
            $table->dropColumn('location_id');
            $table->dropColumn('raw_data');
        });
    }
}
