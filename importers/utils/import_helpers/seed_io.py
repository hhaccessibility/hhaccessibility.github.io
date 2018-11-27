"""
seed_io is a function library for loading and writing seed data.

The seed data is loaded by other modules so it can be merged with new data.
"""

import json

seed_directory = '../../app/database/seeds/data'


def get_seed_file_path_for_table(table_name):
	return seed_directory + '/' + table_name + '.json'


def load_seed_data_from(table_name):
	with open(get_seed_file_path_for_table(table_name), 'rb') as seed_file:
		return json.load(seed_file)


def write_seed_data(table_name, data):
	"""
	Writes the specified data to a seed file for the specified table

	@param data should be a list of dict values representing records from an associated table.
	"""
	if not isinstance(data, list):
		raise ValueError('write_seed_data data must be a list')

	with open(get_seed_file_path_for_table(table_name), 'wb') as seed_file:
		for encoding in ['UTF-8', 'ISO-8859-1', 'latin2', 'cp1252', 'windows-1250']:
			try:
				s = json.dumps(data, sort_keys=True, indent=4, encoding=encoding)
				print('encoded using encoding: ' + encoding)
				break
			except:
				print('Encoding with %s failed.' % encoding)

		s = "\n".join([line.rstrip() for line in s.splitlines()])
		seed_file.write(s)
