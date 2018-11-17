#!/usr/bin/python3

"""
csv_importer is for importing information from a CSV file into our seed data files.

It is to be run from command line such as:
python csv_importer.py locations.csv import_configs/open_street_map.json
or:
python csv_importer.py locations.csv

The import configuration file describes the meaning of each column by relating
them to either fields of the location table or names from the location_tag table.

"""
from import_helpers.task_loader import get_task_info
import csv
import sys, errno
import import_helpers.seed_io as seed_io
from import_helpers.merging import merge_location

task = get_task_info()
import_config = task['import_config']
locations = seed_io.load_seed_data_from('location')
location_tags = seed_io.load_seed_data_from('location_tag')
location_location_tags = seed_io.load_seed_data_from('location_location_tag')
location_duplicates = seed_io.load_seed_data_from('location_duplicate')
user_answers = seed_io.load_seed_data_from('user_answer')
print('loaded ' + str(len(location_duplicates)) + ' location duplicates')

with open(task['csv_filename']) as csv_file:

	if import_config['is_first_row_titles']:
		csv_file.readline() # skip the column titles row.

	csv_reader = csv.reader(csv_file, delimiter=',', quotechar='"')
	num_values = len(import_config['columns'])
	# loop through lines of the file.
	for values in csv_reader:
		if len(values) != num_values:
			print(task['csv_filename'] + ': Line should have ' + str(num_values)
			+ ' but ' + str(len(values)) + ' found in line: ' + str(values))
			sys.exit(errno.EINVAL)

		merge_location(import_config, locations, location_tags, location_location_tags, user_answers, values, location_duplicates)
	
	seed_io.write_seed_data('location', locations)
	seed_io.write_seed_data('location_location_tag', location_location_tags)
	seed_io.write_seed_data('user_answer', user_answers)