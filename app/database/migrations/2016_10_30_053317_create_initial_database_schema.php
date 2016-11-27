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
        Schema::create('data_source', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->string('description', 255)->nullable();
        });
        Schema::create('user', function (Blueprint $table) {
			$table->increments('id');
			$table->string('username', 100)->unique();
			$table->string('email', 255)->unique()->nullable();
			$table->string('first_name', 255)->nullable();
			$table->string('last_name', 255)->nullable();
			$table->char('password_hash', 60);
			$table->string('remember_token', 60)->nullable();
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
        Schema::create('location_group', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
        });
        Schema::create('location', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('owner_user_id')->unsigned()->nullable();
			$table->foreign('owner_user_id')->references('id')->on('user');
			$table->integer('location_group_id')->unsigned()->nullable();
			$table->foreign('location_group_id')->references('id')->on('location_group');
			$table->integer('data_source_id')->unsigned();
			$table->foreign('data_source_id')->references('id')->on('data_source');
			$table->string('name', 255)->nullable();
			$table->string('address', 255)->nullable();
			$table->double('longitude', 11, 8);
			$table->double('latitude', 11, 8);
        });
        Schema::create('user_answer', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('answered_by_user_id')->unsigned();
			$table->foreign('answered_by_user_id')->references('id')->on('user');
			$table->integer('question_id')->unsigned();
			$table->foreign('question_id')->references('id')->on('question');
			$table->integer('location_id')->unsigned();
			$table->foreign('location_id')->references('id')->on('location');
			$table->string('answer_value', 255);
			$table->datetime('when_submitted');
        });
        Schema::create('review_comment', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('answered_by_user_id')->unsigned();
			$table->foreign('answered_by_user_id')->references('id')->on('user');
			$table->integer('location_id')->unsigned();
			$table->foreign('location_id')->references('id')->on('location');
			$table->mediumText('content');
			$table->datetime('when_submitted');
		});
        Schema::create('location_tag', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->unique('name');
			$table->string('description', 255);
		});
        Schema::create('location_location_tag', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('location_id')->unsigned();
			$table->foreign('location_id')->references('id')->on('location');
			$table->integer('location_tag_id')->unsigned();
			$table->foreign('location_tag_id')->references('id')->on('location_tag');
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
        Schema::dropIfExists('location_location_tag');
        Schema::dropIfExists('location_tag');
        Schema::dropIfExists('review_comment');
        Schema::dropIfExists('user_answer');
        Schema::dropIfExists('location');
        Schema::dropIfExists('location_group');
        Schema::dropIfExists('question');
        Schema::dropIfExists('question_category');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('user');
        Schema::dropIfExists('role');
        Schema::dropIfExists('data_source');
    }
}
