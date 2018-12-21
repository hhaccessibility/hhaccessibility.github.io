<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
The initial migration script should have added this constraint but it was forgotten so this script rectifies things.
*/
class AddUniqueConstraintToLocationLocationTag extends Migration
{
    private function removeDuplicateLocationLocationTags()
    {
        // Clean up duplicate records so we can add the new constraint without causing a problem.
        $location_location_tags = DB::table('location_location_tag')
            ->select('location_id', 'location_tag_id', DB::raw('min(id) as id'))
            ->groupBy('location_id', 'location_tag_id')
            ->having(DB::raw('COUNT(1)'), '>', 1)
            ->get();
        // Loop through the groups and delete all except the record that should remain.
        foreach ($location_location_tags as $location_location_tag) {
            DB::table('location_location_tag')->
                where('location_id', '=', $location_location_tag->location_id)->
                where('location_tag_id', '=', $location_location_tag->location_tag_id)->
                where('id', '<>', $location_location_tag->id)->
                delete();
        }
    }

    public function up()
    {
        $this->removeDuplicateLocationLocationTags();
        Schema::table('location_location_tag', function (Blueprint $table) {
            $table->unique(['location_id', 'location_tag_id']);
        });
    }

    public function down()
    {
        Schema::table('location_location_tag', function (Blueprint $table) {
            // Add some indexes to prevent a problem where the dropUnique fails due
            // to the constraint being needed for foreign keys.
            $table->index('location_tag_id', 'location_tag_id_foreign');
            $table->index('location_id', 'location_id_foreign');
            $table->dropUnique('location_location_tag_location_id_location_tag_id_unique');
        });
    }
}
