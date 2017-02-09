"""
seed_io is a function library for loading and writing seed data.

The seed data is loaded by other modules so it can be merged with new data.
"""

import json

seed_directory = '../../app/database/seeds/data'


def get_seed_file_path_for_table(table_name):
	return seed_directory + '/' + table_name + '.json'


def load_seed_data_from(table_name):
	with open(get_seed_file_path_for_table(table_name)) as seed_file:
		return json.load(seed_file)


def write_seed_data(table_name, data):
	"""
	Writes the specified data to a seed file for the specified table

	@param data should be a list of dict values representing records from an associated table.
	"""
	if not isinstance(data, list):
		raise ValueError('write_seed_data data must be a list')

	with open(get_seed_file_path_for_table(table_name), 'w') as seed_file:
		seed_file.write(json.dumps(data, sort_keys=True, indent=4))
