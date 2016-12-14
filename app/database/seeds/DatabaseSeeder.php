<?php

use Illuminate\Database\Seeder;
use Database\Seeds\UserTableSeeder;

function object_to_array($obj) {
	return (array)$obj;
}

class DatabaseSeeder extends Seeder
{
		
	private static function readTableData($json_filename) {
		$content = file_get_contents('database/seeds/data/'.$json_filename);
		$content = json_decode($content);
		if( !is_array($content) )
			throw new Error('Expected array not found in '.$json_filename);

		$content = array_map('object_to_array', $content);
		return $content;
	}
	
	private static function deleteDataFromTables($tableNames)
	{
		foreach ($tableNames as $tableName) {
			DB::table($tableName)->delete();
		}
	}
	
	private static function insertDataToTables($tableNames)
	{
		foreach (array_reverse($tableNames) as $table_name) {
			DB::table($table_name)->insert(DatabaseSeeder::readTableData($table_name . '.json'));
		}
	}
		
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$tables_to_seed_using_json = ['role', 'location_location_tag', 'location',
			'location_group', 'location_tag', 'question',
			'question_category', 'country', 'data_source'];
		$user_data_tables = ['user_answer', 'review_comment'];
		
		DatabaseSeeder::deleteDataFromTables($user_data_tables);
		DatabaseSeeder::deleteDataFromTables($tables_to_seed_using_json);
		DatabaseSeeder::insertDataToTables($tables_to_seed_using_json);

		$this->call('UserTableSeeder');

		DatabaseSeeder::insertDataToTables($user_data_tables);
    }
}
