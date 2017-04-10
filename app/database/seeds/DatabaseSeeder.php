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
		$maxSize = 64000;
		foreach (array_reverse($tableNames) as $table_name) {
			$data = DatabaseSeeder::readTableData($table_name . '.json');
			foreach (array_chunk($data, 1000) as $t) {
                DB::table($table_name)->insert($t);
            }
		}
	}
		
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$tables_to_seed_using_json = ['faq_item', 'role',
			'location_location_tag', 'location_duplicate',
			'location', 'location_group', 'location_tag', 'question',
			'question_category','region', 'country', 'data_source'];
		$user_data_tables = ['user_answer', 'review_comment'];
		
		DatabaseSeeder::deleteDataFromTables($user_data_tables);
		DatabaseSeeder::deleteDataFromTables($tables_to_seed_using_json);
		DatabaseSeeder::insertDataToTables($tables_to_seed_using_json);

		$this->call('UserTableSeeder');

		DatabaseSeeder::insertDataToTables($user_data_tables);
    }
}
