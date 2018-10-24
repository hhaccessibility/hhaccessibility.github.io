<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameToQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question', function (Blueprint $table) {
            $table->string('name', 255);
        });
        // Give unique names to every question so we can add a unique constraint.
        DB::statement("UPDATE question SET name = CONCAT('question_', CAST(id as char(10)));");

        Schema::table('question', function (Blueprint $table) {
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
        Schema::table('question', function (Blueprint $table) {
            $table->dropUnique('question_name_unique');
            $table->dropColumn('name');
        });
    }
}
