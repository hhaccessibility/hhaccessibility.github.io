<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatingCacheToLocationGroup extends Migration
{
    public function up()
    {
        Schema::table('location_group', function (Blueprint $table) {
            $table->boolean('is_automatic_group')->default(false);
            $table->json('ratings_cache')->nullable();
        });
        // Clear all location cache so it is forced to get recalculated.
        DB::statement("UPDATE location SET ratings_cache = null, universal_rating=null");
    }

    public function down()
    {
        Schema::table('location_group', function (Blueprint $table) {
            $table->dropColumn('is_automatic_group');
            $table->dropColumn('ratings_cache');
        });
    }
}
