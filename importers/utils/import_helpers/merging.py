"""
merging.py is a library of functions that help merge location 
information into seed data and prevent duplication of locations if the
same location already exists.
"""

def get_max_id(table_data):
	return max([row['id'] for row in table_data])


def get_location_field(import_config, field_name, values):
	"""
	Returns the value of the specified field by looking it up in the specified values.

	@param field_name is a string, the name of the field to look up.  For example, 'longitude'.
	@param values is a list expected to come from a line from a CSV file
	"""
	i = 0
	for column in import_config['columns']:
		if 'location_field' in column and column['location_field'] == field_name:
			return values[i]
			
		i += 1

	return None

	
def get_id_for_location_tag(location_tags, location_tag_name):
	for location_tag in location_tags:
		if location_tag['name'] == location_tag_name:
			return location_tag['id']

	raise ValueError('Unable to find location tag with name ' + location_tag_name)

	
def get_id_of_matching_location(import_config, locations, values):
	"""
	Tries to find a location matching the latitude and longitude closely and matching names.
	"""
	
	# Relying on coordinate difference keeps math simple but also means 
	# the threshold is wider for locations near the equator and narrower for locations near the poles.
	coordinate_difference_threshold = 0.003

	values_longitude = float(get_location_field(import_config, 'longitude', values).strip())
	values_latitude = float(get_location_field(import_config, 'latitude', values).strip())
	values_name = get_location_field(import_config, 'name', values).strip().lower()

	for location in locations:
		location['longitude'] = float(location['longitude'])
		location['latitude'] = float(location['latitude'])
		# if not close enough, skip.
		if ( abs(location['longitude'] - values_longitude) > coordinate_difference_threshold or
		abs(location['latitude'] - values_latitude) > coordinate_difference_threshold ):
			continue

		if values_name == location['name'].strip().lower():
			return location['id']
	
	return None

	
def matches_true(value):
	"""
	Returns True if value matches one of a few values that could be used to
	indicate true.  More values are interpretted as true to put fewer
	constraints on the required CSV format.
	"""
	if isinstance(value, str):
		value = value.strip().lower()
	
	return value in ['true', '1', 'yes', 'y']


def set_every_key(locations, new_location):
	if len(locations) == 0:
		return

	nullable_fields = ['owner_user_id', 'universal_rating',
	'location_group_id', 'external_web_url']
	for key in locations[0].keys():
		if key not in new_location:
			if key in nullable_fields:
				new_location[key] = None
			else:
				new_location[key] = ''
	
	return new_location


def sanitize(location_field, value):
	"""
	Converts to appropriate data type
	"""
	if isinstance(value, str):
		if location_field in ['longitude', 'latitude']:
			value = float(value.strip())
	
	return value
	

def merge_location(import_config, locations, location_tags, location_location_tags, values):
	matching_location_id = get_id_of_matching_location(import_config, locations, values)
	if matching_location_id is not None:
		print('matching location found for ' + get_location_field(import_config, 'name', values) + ' id ' + str(matching_location_id))
		return

	new_location = {
		'id': get_max_id(locations) + 1,
		'data_source_id': import_config['data_source_id']
	}
	new_location = set_every_key(locations, new_location)

	tag_ids = []
	i = 0
	for column in import_config['columns']:
		if 'location_field' in column:
			new_location[column['location_field']] = sanitize(column['location_field'], values[i])
		elif 'location_tag_name' in column:
			if matches_true(values[i]):
				location_tag_id = get_id_for_location_tag(location_tags, column['location_tag_name'])
				tag_ids.append(location_tag_id)

		i += 1

	locations.append(new_location)
	location_location_tag_id = 1 + get_max_id(location_location_tags)
	for tag_id in tag_ids:
		location_location_tags.append({
			'id': location_location_tag_id,
			'location_tag_id': tag_id,
			'location_id': new_location['id']
		})
		location_location_tag_id += 1