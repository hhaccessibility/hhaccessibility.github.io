<?php

use Illuminate\Database\Seeder;
use Database\Seeds\UserTableSeeder;

function object_to_array($obj) {
	return (array)$obj;
}

class DatabaseSeeder extends Seeder
{
		
	public static function readTableData($json_filename) {
		$content = file_get_contents('database/seeds/data/'.$json_filename);
		$content = json_decode($content);
		if( !is_array($content) )
			throw new Error('Expected array not found in '.$json_filename);

		$content = array_map('object_to_array', $content);
		return $content;
	}
		
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$tables_to_seed_using_json = ['role', 'building_building_tag', 'building',
			'building_group', 'building_tag', 'question',
			'question_category', 'country', 'data_source'];
		foreach ($tables_to_seed_using_json as $table_name) {
			DB::table($table_name)->delete();
		}
		
		foreach (array_reverse($tables_to_seed_using_json) as $table_name) {
			DB::table($table_name)->insert(DatabaseSeeder::readTableData($table_name . '.json'));
		}
		
		$this->call('UserTableSeeder');
    }
}
