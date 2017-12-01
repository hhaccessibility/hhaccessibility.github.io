"""
This module was written to test the completeness of the data in location_tag_to_type.json.

It prints nothing when all the Google Place types as defined in googleplaces.types are listed at least once in location_tag_to_type.json.

"""
import location_tags
from googleplaces import types
import json

location_tag_types = None
with open('location_tag_to_type.json', 'rb') as f:
	location_tag_types = json.loads(f.read())

types_from_location_tags_data = []
for key in location_tag_types:
	types_from_location_tags_data.extend(location_tag_types[key])

# loop through all of the types.
for prop_name in dir(types):
	if prop_name.startswith('TYPE_') and getattr(types, prop_name) not in types_from_location_tags_data:
		print prop_name + "\r\n"
		# see if the current type is not in any of the location tag data.
	