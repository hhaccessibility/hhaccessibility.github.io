<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
This migration adds a new ratings_cache field to the location table.

The new ratings_cache is intended to speed up personal rating calculations.
The personal rating values need to be calculated frequently in the location search feature.
*/
class AddLocationRatingCache extends Migration
{
	public function up()
	{
		Schema::table('location', function (Blueprint $table) {
			$table->json('ratings_cache')->nullable();
		});
	}

	public function down()
	{
		Schema::table('location', function (Blueprint $table) {
			$table->dropColumn('ratings_cache');
		});
	}
}
