<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesUserAnswerTable extends Migration
{
    public function up()
    {
        Schema::table('user_answer', function (Blueprint $table) {
            $table->softDeletes();
        });
        // loop through all distinct tuples of user, location, and question.
        $tuples = DB::table('user_answer')->
            select('question_id', 'location_id', 'answered_by_user_id')->
            distinct()->get();
        foreach ($tuples as $tuple) {
            // Get latest user_answer.
            $latest_user_answer = DB::table('user_answer')->
                where('question_id', '=', $tuple->question_id)->
                where('location_id', '=', $tuple->location_id)->
                where('answered_by_user_id', '=', $tuple->answered_by_user_id)->
                orderBy('when_submitted', 'desc')->
                first();
            DB::table('user_answer')->
                where('question_id', '=', $tuple->question_id)->
                where('location_id', '=', $tuple->location_id)->
                where('answered_by_user_id', '=', $tuple->answered_by_user_id)->
                where('id', '<>', $latest_user_answer->id)->
                update(['deleted_at' => date('Y-m-d H:i:s')]);
        }
    }

    public function down()
    {
        Schema::table('user_answer', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
