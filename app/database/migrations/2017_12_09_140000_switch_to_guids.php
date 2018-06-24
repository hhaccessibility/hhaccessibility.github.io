<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

global $fieldsData;
$fieldsData = file_get_contents("database/migrations/data/initial_guids_migration.json");
$fieldsData = json_decode($fieldsData);

function getPrimaryKeysToUpdate() {
	global $fieldsData;
	return $fieldsData->primary_key_tables;
}

function getFieldsToUpdate() {
	global $fieldsData;
	return get_object_vars($fieldsData->foreign_keys);
}

function getAffectedTableNames() {
	$fieldsToUpdate = getFieldsToUpdate();
	$keys = array_keys($fieldsToUpdate);

	return array_unique(array_merge(getPrimaryKeysToUpdate(), $keys));
}

function deleteWhatCantBeConvertedToInt($queryTable, $fieldName) {
	$queryTable
		->whereNotNull($fieldName)
		->where($fieldName, 'NOT LIKE', '00000000-0000-0000-0000-%')
		->delete();
}

function getAffectedFieldsForTable($tableName) {
	$queryTable = DB::table($tableName);
	$fieldNames = [];
	if (in_array($tableName, getPrimaryKeysToUpdate())) {
		$fieldNames []= 'id';
	}
	if (array_key_exists($tableName, getFieldsToUpdate())) {
		$fieldsToUpdate = getFieldsToUpdate()[$tableName];
		$fieldNames = array_merge($fieldsToUpdate, $fieldNames);
	}
	return $fieldNames;
}

/*
This migration converts various autoincrement primary ids to guids.

This change will help merge information gathered from seed data
into our deployments.  This is how new information collected from
our import tools will end up in our public deployments.
*/

function idToGuid($id) {
	$result = ''.$id; // decimal representation.
	while (strlen($result) < 12) {
		$result = '0'.$result;
	}
	return '00000000-0000-0000-0000-'.$result;
}


function getForeignTableFromForeignField($fieldName) {
	$otherTableName = $fieldName;
	if (strrpos($otherTableName, '_id') === strlen($otherTableName) - 3 ) {
		$otherTableName = substr($otherTableName, 0, strlen($otherTableName) - 3);
	}
	if (strpos($otherTableName, '_user') !== FALSE) {
		$otherTableName = 'user';
	}
	return $otherTableName;
}


class Test
{
	public static function convertData(string $tableName)
	{
		$queryTable = DB::table($tableName);
		$fieldNames = getAffectedFieldsForTable($tableName);
		foreach ($fieldNames as $fieldName) {
			$queryTable->update(['new_'.$fieldName => DB::raw("IF(".$fieldName." is null, null, concat('00000000-0000-0000-0000-', LPAD(".$fieldName.", 12, '0')))")]);
		}
	}

	public static function reverseConvertData(string $tableName)
	{
		$queryTable = DB::table($tableName);
		$fieldNames = getAffectedFieldsForTable($tableName);
		foreach ($fieldNames as $fieldName) {
			$newFieldName = 'new_'.$fieldName;
			$queryTable->update([$newFieldName => DB::raw("IF(".$fieldName." is null, null, CONVERT(SUBSTRING($fieldName,28),UNSIGNED INTEGER))")]);
		}
	}

	public function addForeignKeyConstraints($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		return function (Blueprint $table) use( &$fieldNames) {
			foreach ($fieldNames as $fieldName) {
				if( $fieldName !== 'id' ) {
					$otherTableName = getForeignTableFromForeignField($fieldName);
					$table->foreign($fieldName)->references('id')->on($otherTableName);
				}
			}
		};
	}

	public function dropOriginalFieldsAndRename($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		return function (Blueprint $table) use( &$fieldNames) {
			foreach ($fieldNames as $fieldName) {
				if( $fieldName === 'id' ) {
					$table->integer($fieldName)->unsigned()->change();
					$table->dropPrimary();
				}
				// drop the column.
				$table->dropColumn($fieldName);
				$table->renameColumn('new_'.$fieldName, $fieldName);
				if( $fieldName === 'id' ) {
					$table->integer($fieldName)->unsigned()->change();
					$table->primary(['id']);
				}
			}
		};
	}

	public function dropGuidFieldsAndRename($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		return function (Blueprint $table) use( &$fieldNames) {
			foreach ($fieldNames as $fieldName) {
				if( $fieldName === 'id' ) {
					$table->string($fieldName, 36)->change();
					$table->dropPrimary();
				}
				// drop the column.
				$table->dropColumn($fieldName);
				$table->renameColumn('new_'.$fieldName, $fieldName);
				if( $fieldName === 'id' ) {
					$table->primary(['id']);
				}
			}
		};
	}

	public function convertIdToIntAndDropForeignConstraints($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		return function (Blueprint $table) use( &$fieldNames, &$tableName) {
			foreach ($fieldNames as $fieldName) {
				// Drop foreign key constraints on $fieldName.
				if ( $fieldName !== 'id' && $fieldName !== '' ) {
					$constraintName = $tableName.'_'.$fieldName.'_foreign';
					$table->dropForeign($constraintName);
				}
			}
		};
	}

	public function convertIdToGuidAndDropForeignConstraints($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		return function (Blueprint $table) use( &$fieldNames, &$tableName) {
			foreach ($fieldNames as $fieldName) {
				$table->uuid('new_' . $fieldName)->nullable();

				// Drop foreign key constraints on $fieldName.
				if( $fieldName !== 'id' && $fieldName !== '' ) {
					$constraintName = $tableName.'_'.$fieldName.'_foreign';
					$table->dropForeign($constraintName);
				}
			}
		};
	}

	public function addNewIntFields($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		return function (Blueprint $table) use( &$fieldNames, &$tableName) {
			// Delete data that can't be converted to int format.
			foreach ($fieldNames as $fieldName) {
				if( $fieldName !== 'id' ) {
				}
			}
			$newFieldNames = [];
			foreach ($fieldNames as $fieldName) {
				$newFieldNames []= 'new_' . $fieldName;
			}
			foreach ($newFieldNames as $newFieldName) {
				$table->integer($newFieldName)->unsigned()->nullable();
			}
		};
	}

	public function undoChange($tableName)
	{
		$fieldNames = getAffectedFieldsForTable($tableName);
		$newFieldNames = [];
		foreach ($fieldNames as $fieldName) {
			$newFieldNames []= 'new_' . $fieldName;
		}
		return function (Blueprint $table) use( &$newFieldNames) {
			$table->dropColumn($newFieldNames);
		};
	}
}


class SwitchToGuids extends Migration
{
	private function createConstraints()
	{
		Schema::table('user_role', function(Blueprint $table) {
			$table->unique(array('role_id', 'user_id'));
			$table->foreign('role_id')->references('id')->on('role');
		});
		Schema::table('user_question', function(Blueprint $table) {
			$table->unique(array('question_id', 'user_id'));
			$table->foreign('question_id')->references('id')->on('question');
		});
	}

	private function dropConstraints()
	{
		Schema::table('user_role', function(Blueprint $table) {
			$table->dropForeign('user_role_role_id_foreign');
			$table->dropUnique('user_role_role_id_user_id_unique');
		});
		Schema::table('user_question', function(Blueprint $table) {
			$table->dropForeign('user_question_question_id_foreign');
		});
		Schema::table('user_question', function(Blueprint $table) {
			$table->dropUnique('user_question_question_id_user_id_unique');
		});
	}

	public function up()
	{
		foreach (getAffectedTableNames() as $tableName) {
			$object = new Test();
			$function = $object->convertIdToGuidAndDropForeignConstraints($tableName);
			Schema::table($tableName, $function);
			Test::convertData($tableName);
		}
		$this->dropConstraints();
		foreach (getAffectedTableNames() as $tableName) {
			Schema::table($tableName, $object->dropOriginalFieldsAndRename($tableName));
		}
		foreach (getAffectedTableNames() as $tableName) {
			Schema::table($tableName, $object->addForeignKeyConstraints($tableName));
		}
		$this->createConstraints();
	}

	public function down()
	{
		$this->dropConstraints();
		foreach (getAffectedTableNames() as $tableName) {
			$object = new Test;
			$function = $object->addNewIntFields($tableName);
			Schema::table($tableName, $function);
		}

		foreach (getPrimaryKeysToUpdate() as $tableName) {
			deleteWhatCantBeConvertedToInt(DB::table($tableName), 'id');
		}

		foreach (getAffectedTableNames() as $tableName) {
			Test::reverseConvertData($tableName);
		}
		foreach (getAffectedTableNames() as $tableName) {
			$object = new Test;
			$function = $object->convertIdToIntAndDropForeignConstraints($tableName);
			Schema::table($tableName, $function);
		}
		foreach (getAffectedTableNames() as $tableName) {
			Schema::table($tableName, $object->dropGuidFieldsAndRename($tableName));
		}

		foreach (getAffectedTableNames() as $tableName) {
			Schema::table($tableName, $object->addForeignKeyConstraints($tableName));
		}

		$this->createConstraints();
	}
}
