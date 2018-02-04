<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
This migration adds a new is_required_config to the question table.

is_required_config will be useful for determining if the question is
applicable based on associated question_tag or answers to other questions.

For example, If a location has an elevator, asking if the doorway is wide enough
for a wheelchair is applicable.
*/
class AddRequiredConfigToQuestionTable extends Migration
{
    public function up()
    {
        Schema::table('question', function (Blueprint $table) {
            $table->json('is_required_config')->nullable();
        });
    }

    public function down()
    {
        Schema::table('question', function (Blueprint $table) {
            $table->dropColumn('is_required_config');
        });
    }
}
