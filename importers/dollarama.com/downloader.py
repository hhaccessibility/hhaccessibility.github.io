import requests
import os.path
from lxml import etree


# Removes some substrings that are meaningless.
def sanitize_name(name):
	name = name.replace('&#39;', "'")
	substrings_to_remove = ['&amp;', '&nbsp;']
	for substring in substrings_to_remove:
		name = name.replace(substring, ' ')
	return name.strip()


def marker_to_location_dict(marker_element):
	keys_of_interest = ['name', 'lat', 'lng', 'address', 'city', 
		'state', 'zip', 'hours', 'phone']
	result = {}
	for key in keys_of_interest:
		result[key] = marker_element.xpath('@' + key)[0].strip()

	result['name'] = sanitize_name(result['name'])
	if not result['name']:
		result['name_with_place'] = 'Dollarama'
	else:
		result['name_with_place'] = 'Dollarama - ' + result['name']

	result['name'] = 'Dollarama'
	return result


def get_locations(latitude, longitude, radius):
	cache_file_name = 'raw_data/dollarama_%s_%s_%d.xml' % (latitude, longitude, radius)
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			content = f.read()
	else:
		url = 'http://www.dollarama.com/wp-content/plugins/store-locator/sl-xml.php?mode=gen&lat=%s&lng=%s&radius=%d' %(latitude, longitude, radius)
		content = requests.get(url).content
		with open(cache_file_name, 'w') as f:
			f.write(content)
	document = etree.fromstring(content)
	markers = document.cssselect('marker')
	locations = []
	# loop through all 'marker' elements.
	for marker_element in markers:
		locations.append(marker_to_location_dict(marker_element))
	return locations


def get_coordinates():
	with open('coordinates.txt', 'r') as f:
		lines = f.read().split("\n")
		result = []
		for line in lines:
			if ',' in line:
				latitude = float(line[:line.find(',')])
				longitude = float(line[line.find(',') + 1:])
				coordinate_pair = (latitude, longitude)
				result.append(coordinate_pair)
		return result


def merge_location_sets(locations1, locations2):
	locations = {}
	for location_set in [locations1, locations2]:
		for location in location_set:
			locations[location['address']] = location
	return locations.values()


def get_all_locations(radius):
	locations = []
	for coordinate_pair in get_coordinates():
		new_locations = get_locations(coordinate_pair[0], coordinate_pair[1], radius)
		locations = merge_location_sets(locations, new_locations)
	return locations


def get_all_locations_various_radii():
	radii = [5, 10, 20, 100]
	locations = []
	for radius in radii:
		locations = merge_location_sets(locations, get_all_locations(radius))
	return locations


if __name__=="__main__":
	locations = get_all_locations_various_radii()
	print('num locations found: %d' % len(locations))