<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
This migration adds a new explanation field to the question table.

This should make it easier to explain what questions mean when they're difficult to understand.
*/
class AddQuestionExplanationField extends Migration
{
	public function up()
	{
		Schema::table('question', function (Blueprint $table) {
			$table->text('explanation')->nullable();
		});
	}

	public function down()
	{
		Schema::table('question', function (Blueprint $table) {
			$table->dropColumn('explanation');
		});
	}
}
