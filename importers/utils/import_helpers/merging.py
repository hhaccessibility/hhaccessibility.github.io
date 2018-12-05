"""
merging.py is a library of functions that help merge location 
information into seed data and prevent duplication of locations if the
same location already exists.
"""
import import_helpers.location_groups as location_groups
import import_helpers.location_tags
import import_helpers.guid_generator as guid_generator
import json
from datetime import datetime
from import_config_interpreter import get_location_field
import duplicate_detection


def get_max_id(table_data):
	return max([row['id'] for row in table_data])


def get_id_for_location_tag(location_tags, location_tag_name):
	for location_tag in location_tags:
		if location_tag['name'] == location_tag_name:
			return location_tag['id']

	raise ValueError('Unable to find location tag with name ' + location_tag_name)

	
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
	'location_group_id', 'external_web_url', 'destroy_location_event_id']
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
	

def is_location_of_interest(location_name):
	location_name = location_name.strip().lower()
	if location_name in ['windsor']:
		return False

	return True


def find_by_id(list1, id_value):
	return [element for element in list1 if element['id'] == id_value][0]


def get_user_answers_from(import_config, location_id, values):
	result = []
	i = 0
	for column in import_config['columns']:
		if 'question_ids' in column:
			answer_value = matches_true(values[i])
			if answer_value:
				answer_value = 1
			else:
				answer_value = 0
			for question_id in column['question_ids']:
				result.append({
					'answer_value': answer_value,
					'answered_by_user_id': import_config['import_user_id'],
					'question_id': question_id,
					'location_id': location_id,
					'id': guid_generator.get_guid(),
					'when_submitted': datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S')
				})
		i += 1
	return result


def merge_location_information(import_config, location, user_answers, values):
	fields_to_merge = ['location_group_id', 'address', 'phone_number', 'external_web_url']
	for field_name in fields_to_merge:
		val = get_location_field(import_config, field_name, values)
		if val and not location[field_name]:
			location[field_name] = val

	# Look into merging answers into the location.
	if 'import_user_id' in import_config:
		matched_user_answers = [a for a in user_answers if
			a['answered_by_user_id'] == import_config['import_user_id'] and a['location_id'] == location['id']]
		if len(matched_user_answers) == 0:
			new_answers = get_user_answers_from(import_config, location['id'], values)
			print('merging answers into location ' + location['id'])
			for new_answer in new_answers:
				user_answers.append(new_answer)


def get_appropriate_location_tags(location, location_tags):
	"""
	Adds appropriate location tags for the specified location based on the location's name.
	For example, if the location's name was 'Tim Hortons', the Restaurant tag would be added.
	"""
	tag_ids = [tag['id'] for tag in location_tags]
	return import_helpers.location_tags.get_all_appropriate_location_tags_for(location['name'], tag_ids)


def merge_location(import_config, locations, location_tags,
location_location_tags, user_answers, values, location_duplicates):
	location_name = get_location_field(import_config, 'name', values)
	if not is_location_of_interest(location_name):
		print('location is not of interest: ' + location_name)
		return

	matching_location_id = duplicate_detection.get_id_of_matching_location(import_config,
		locations, values, location_duplicates)
	if matching_location_id is not None:
		print('matching location found for ' + location_name + ' id ' + str(matching_location_id))
		merge_location_information(import_config, find_by_id(locations, matching_location_id), user_answers, values)
		return

	new_location = {
		'id': guid_generator.get_guid(),
		'data_source_id': import_config['data_source_id']
	}
	for field_name in ['latitude', 'longitude']:
		new_location[field_name] = get_location_field(import_config, field_name, values)

	if 'location_group_id' in import_config:
		new_location['location_group_id'] = import_config['location_group_id']

	new_location = set_every_key(locations, new_location)
	# include any user answers that might be extractable from values.
	new_user_answers = get_user_answers_from(import_config, new_location['id'], values)
	for user_answer in new_user_answers:
		user_answers.append(user_answer)

	tag_ids = []
	if 'location_tag_names' in import_config:
		for location_tag_name in import_config['location_tag_names']:
				location_tag_id = get_id_for_location_tag(location_tags, location_tag_name)
				tag_ids.append(location_tag_id)

	i = 0
	for column in import_config['columns']:
		if 'location_field' in column:
			new_location[column['location_field']] = sanitize(column['location_field'], values[i])
		elif 'location_tag_name' in column:
			if matches_true(values[i]):
				location_tag_id = get_id_for_location_tag(location_tags, column['location_tag_name'])
				tag_ids.append(location_tag_id)

		i += 1

	if 'location_group_id' not in new_location or not new_location['location_group_id']:
		new_location['location_group_id'] = location_groups.get_location_group_for(new_location['name'])
	locations.append(new_location)

	# If no location tags are selected yet, use the location's name to determine appropriate tags.
	if len(tag_ids) == 0:
		tag_ids = get_appropriate_location_tags(new_location, location_tags)

	for tag_id in tag_ids:
		location_location_tags.append({
			'id': guid_generator.get_guid(),
			'location_tag_id': tag_id,
			'location_id': new_location['id']
		})
