<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSoftDeletesToSuggestionTable extends Migration
{
    public function up()
    {
        Schema::table('suggestion', function (Blueprint $table) {
            $table->softDeletes();
        });
        // Make location's name not nullable.
        Schema::table('location', function (Blueprint $table) {
            $table->string('name', 255)->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('location', function (Blueprint $table) {
            $table->string('name', 255)->nullable(true)->change();
        });
        Schema::table('suggestion', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
