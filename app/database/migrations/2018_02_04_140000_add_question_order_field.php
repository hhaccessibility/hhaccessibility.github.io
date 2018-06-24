<?php

use \App\Question;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
This migration adds a new order field to the question table.

This should make it easier to insert new questions into the question list.
*/
class AddQuestionOrderField extends Migration
{
	public function up()
	{
		Schema::table('question', function (Blueprint $table) {
			$table->integer('order')->unsigned();
		});

		// Initialize the order values so they'll be unique.
		$questions = Question::orderBy('question_category_id')->orderBy('id')->get();
		$current_question_category_id = null;
		foreach ($questions as $question) {
			if ($question->question_category_id !== $current_question_category_id) {
				/* 100 instead of 1 because importers/utils/json_to_db.py will
				update order again. We want to avoid unique constraint violations as that happened.
				*/
				$order = 100;
				$current_question_category_id = $question->question_category_id;
			}
			$question->order = $order;
			$question->save();

			$order ++;
		}

		// Add unique constraint.
		Schema::table('question', function (Blueprint $table) {
			$table->unique(array('question_category_id', 'order'));
		});
	}

	public function down()
	{
		Schema::table('question', function (Blueprint $table) {
			$table->dropUnique('question_question_category_id_order_unique');
			$table->dropColumn('order');
		});
	}
}
