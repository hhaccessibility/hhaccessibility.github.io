import json

location_tag_types = None
with open('location_tag_to_type.json', 'rb') as f:
	location_tag_types = json.loads(f.read())


def get_location_tag_keys():
	result = location_tag_types.keys()
	result.remove('is_not_of_interest')
	result.remove('is_uncategorized')
	return result


def is_of_interest(place):
	return not is_matching_location_tag('is_not_of_interest', place)


def is_matching_location_tag(key, place):
	for place_type in location_tag_types[key]:
		if place_type in place.types:
			return True

	return False
