"""
This script is useful for filtering locations from a CSV to a specific latitude and longitude range.
"""
import csv
import sys
from import_helpers.task_loader import get_import_config
from import_helpers.import_config_interpreter import get_location_field


def filter_csv(min_longitude, max_longitude, min_latitude, max_latitude, import_config, csv_input, csv_output):
	with open(csv_input, 'r') as csv_input_file:
		with open(csv_output, 'w') as csv_output_file:
			# if there are titles in the first line, copy it.
			if import_config['is_first_row_titles']:
				line = csv_input_file.readline()
				csv_output_file.write(line)

			csv_reader = csv.reader(csv_input_file, delimiter=',', quotechar='"')
			writer = csv.writer(csv_output_file, delimiter=',',
				quotechar='"', quoting=csv.QUOTE_ALL)
			num_values = len(import_config['columns'])
			# loop through lines of the file.
			for values in csv_reader:
				if len(values) != num_values:
					print(task['csv_filename'] + ': Line should have ' + str(num_values)
					+ ' but ' + str(len(values)) + ' found in line: ' + str(values))
					sys.exit(errno.EINVAL)
				lon = float(get_location_field(import_config, 'longitude', values).strip())
				lat = float(get_location_field(import_config, 'latitude', values).strip())
				if lon <= max_longitude and lon >= min_longitude and lat <= max_latitude and lat >= min_latitude:
					writer.writerow(values)


is_valid = True
if len(sys.argv) < 8:
	print('Usage: min_longitude max_longitude min_latitude max_latitude csv_import_config_json_file csv_input csv_output')
	is_valid = False
else:
	import_config_filename = sys.argv[5].strip()
	if '.json' not in import_config_filename:
		print('The import config filename must end with .json.  Instead you specified %s.' % import_config_filename)
		is_valid = False

	csv_input = sys.argv[6].strip()
	csv_output = sys.argv[7].strip()
	if csv_input == csv_output:
		print('The CSV input and output file names must be different to avoid data loss.  You specified %s and %s.' % (csv_input, csv_output))
		is_valid = False
	if '.csv' not in csv_input or '.csv' not in csv_output:
		print('The CSV input and output files must end with .csv.  You specified %s and %s' % (csv_input, csv_output))
		is_valid = False

	import_config = get_import_config(import_config_filename)
	if is_valid:
		min_longitude = float(sys.argv[1].strip())
		max_longitude = float(sys.argv[2].strip())
		min_latitude = float(sys.argv[3].strip())
		max_latitude = float(sys.argv[4].strip())

		# Swap longitudes if they're out of order.
		if min_longitude > max_longitude:
			temp = max_longitude
			max_longitude = min_longitude
			min_longitude = temp

		# Swap latitudes if they're out of order.
		if min_latitude > max_latitude:
			temp = max_latitude
			max_latitude = min_latitude
			min_latitude = temp

		if min_latitude < -90 or max_latitude > 90:
			print('The latitude range must be between -90 and 90 but %f to %f specified.' % (min_latitude, max_latitude))
			is_valid = False
		if min_longitude < -180 or max_longitude > 180:
			print('The longitude range must be between -180 and 180 but %f to %f specified.' % (min_longitude, max_longitude))
			is_valid = False
		if is_valid:
			filter_csv(min_longitude, max_longitude, min_latitude, max_latitude, import_config, csv_input, csv_output)
