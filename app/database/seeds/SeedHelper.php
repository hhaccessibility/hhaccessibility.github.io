<?php

function object_to_array($obj) {
	return (array)$obj;
}

class SeedHelper
{
	public static function readTableData($json_filename) {
		$content = file_get_contents('database/seeds/data/'.$json_filename);
		$content = json_decode($content);
		if( !is_array($content) )
			throw new Error('Expected array not found in '.$json_filename);

		$content = array_map('object_to_array', $content);
		return $content;
	}
}
