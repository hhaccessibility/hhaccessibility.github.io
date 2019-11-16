"""
seed_io is a function library for loading and writing seed data.

The seed data is loaded by other modules so it can be merged with new data.
"""

import json
import types
import os

seed_directory = '../../app/database/seeds/data'


def get_seed_file_path_for_table(table_name):
	return seed_directory + '/' + table_name + '.json'


def get_location_id_from_image_path(path):
	token = 'location_images'
	if token in path:
		path = path[path.rfind(token) + len(token):]
	path = path.replace('/', '').replace('\\', '')
	return path


def get_image_id_from_jpg_path(path):
	if '.jpg' in path:
		path = path[:-len('.jpg')]
	return path


def load_seed_data_from_image_files():
	path = seed_directory + '/location_images'
	default_uploader_user_id = '00000000-0000-0000-0000-000000000001'
	result = []
	# loop through location directories.
	for location_dir in os.walk(path):
		print('location_dir ' + str(location_dir) + "\n")
		location_id = get_location_id_from_image_path(location_dir[0])
		for image_path in location_dir[2]:
			image_id = get_image_id_from_jpg_path(image_path)
			path = location_dir[0].replace('\\', '/') + '/' + image_path
			with open(path, 'rb') as f:
				raw_data = f.read()

			result.append({
				'id': image_id,
				'location_id': location_id,
				'uploader_user_id': default_uploader_user_id,
				'raw_data': raw_data
			})
	return result


def load_seed_data_from(table_name):
	if table_name == 'image':
		return load_seed_data_from_image_files()

	with open(get_seed_file_path_for_table(table_name), 'r') as seed_file:
		return json.load(seed_file)


def write_seed_data(table_name, data):
	"""
	Writes the specified data to a seed file for the specified table

	@param data should be a list of dict values representing records from an associated table.
	"""
	if isinstance(data, types.GeneratorType):
		data = list(data)
	if not isinstance(data, list):
		raise ValueError('write_seed_data data must be a list or generator')

	with open(get_seed_file_path_for_table(table_name), 'w') as seed_file:
		for encoding in ['UTF-8', 'ISO-8859-1', 'latin2', 'cp1252', 'windows-1250']:
			try:
				print('about to try encoding with %s' % encoding)
				s = json.dumps(data, sort_keys=True, indent=4, encoding=encoding)
				print('encoded using encoding: ' + encoding)
				break
			except:
				print('Encoding with %s failed.' % encoding)

		s = "\n".join([line.rstrip() for line in s.splitlines()])
		seed_file.write(s)
