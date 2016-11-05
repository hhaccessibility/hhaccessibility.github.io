<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialDatabaseSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
			$table->increments('id');
			$table->string('username', 100)->unique();
			$table->string('password_hash', 32);
			$table->double('search_radius_km', 11, 6)->nullable();
			$table->double('longitude', 11, 8)->nullable();
			$table->double('latitude', 11, 8)->nullable();
        });
        Schema::create('role', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100)->unique();
			$table->string('description', 255);
        });
        Schema::create('user_role', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('user');
			$table->integer('role_id')->unsigned();
			$table->foreign('role_id')->references('id')->on('role');
			$table->unique(array('role_id', 'user_id'));
		});
		Schema::create('question_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
        });
        Schema::create('question', function (Blueprint $table) {
			$table->increments('id');
			$table->string('question_html');
			$table->integer('question_category_id')->unsigned()->nullable();
			$table->foreign('question_category_id')->references('id')->on('question_category');
        });
        Schema::create('building_group', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
        });
        Schema::create('building', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('owner_user_id')->unsigned()->nullable();
			$table->foreign('owner_user_id')->references('id')->on('user');
			$table->integer('building_group_id')->unsigned()->nullable();
			$table->foreign('building_group_id')->references('id')->on('building_group');
			$table->string('name', 255)->nullable();
			$table->double('longitude', 11, 8);
			$table->double('latitude', 11, 8);
        });
        Schema::create('user_answer', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('answered_by_user_id')->unsigned();
			$table->foreign('answered_by_user_id')->references('id')->on('user');
			$table->integer('question_id')->unsigned();
			$table->foreign('question_id')->references('id')->on('question');
			$table->integer('building_id')->unsigned();
			$table->foreign('building_id')->references('id')->on('building');
			$table->string('answer_value', 255);
			$table->datetime('when_submitted');
        });
        Schema::create('review_comment', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('answered_by_user_id')->unsigned();
			$table->foreign('answered_by_user_id')->references('id')->on('user');
			$table->integer('building_id')->unsigned();
			$table->foreign('building_id')->references('id')->on('building');
			$table->mediumText('content');
			$table->datetime('when_submitted');
		});
        Schema::create('building_tag', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->unique('name');
		});
        Schema::create('building_building_tag', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('building_id')->unsigned();
			$table->foreign('building_id')->references('id')->on('building');
			$table->integer('building_tag_id')->unsigned();
			$table->foreign('building_tag_id')->references('id')->on('building_tag');
		});		
        Schema::create('country', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->unique('name');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country');
		Schema::dropIfExists('building_building_tag');
        Schema::dropIfExists('building_tag');
        Schema::dropIfExists('review_comment');
        Schema::dropIfExists('user_answer');
        Schema::dropIfExists('building');
        Schema::dropIfExists('building_group');
        Schema::dropIfExists('question');
        Schema::dropIfExists('question_category');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('user');
        Schema::dropIfExists('role');
    }
}
