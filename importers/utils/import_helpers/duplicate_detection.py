import re
import string
import unicodedata
from import_config_interpreter import get_location_field
import math

distance_threshold_km = 0.2
distance_threshold_for_very_similar_information = 0.4
distance_threshold_km_for_recorded_duplicate = 0.5


def lcs(S,T):
	"""
	Returns longest common substring.  
	If there are multiple equal length strings, only one of them is returned.

	Copied from: 
	https://bogotobogo.com/python/python_longest_common_substring_lcs_algorithm_generalized_suffix_tree.php
	"""
	m = len(S)
	n = len(T)
	counter = [[0]*(n+1) for x in range(m+1)]
	longest = 0
	lcs_set = set()
	for i in range(m):
		for j in range(n):
			if S[i] == T[j]:
				c = counter[i][j] + 1
				counter[i+1][j+1] = c
				if c > longest:
					lcs_set = set()
					longest = c
					lcs_set.add(S[i-c+1:i+1])
				elif c == longest:
					lcs_set.add(S[i-c+1:i+1])

	lcs_set = list(lcs_set)
	if len(lcs_set) == 0:
		return ''
	else:
		return lcs_set[0]


def get_direct_distance(lat1, lon1, lat2, lon2):
	"""
	Returns distance in km across the Earth's curvature between the specified coordinates.
	
	lat1, lon1, lat2, lon2 should be in degrees.
	
	This is basically a translation of a very similar method in BaseUser class implemented in PHP.
	"""
	earthRadius = 6371 # km
	lon1 = math.radians(lon1)
	lat1 = math.radians(lat1)
	lon2 = math.radians(lon2)
	lat2 = math.radians(lat2)
	deltaLong = lon2 - lon1
	deltaLat = lat2 - lat1
	a = ( math.sin(deltaLat / 2) * math.sin(deltaLat / 2) +
		math.cos(lat1) * math.cos(lat2) *
		math.sin(deltaLong / 2) * math.sin(deltaLong / 2) )
	c = 2 * math.atan2( math.sqrt( a ), math.sqrt( 1 - a ) )
	return earthRadius * c


# Returns only the digits contained in the specified string.
# For example, strip_to_digits('1-519-123-1234') == '15191231234'
def strip_to_digits(s):
	if not s:
		return ''
	return ''.join([i for i in s if i.isdigit()])


def is_very_similar_information(import_config, values, location):
	if not is_name_at_least_vaguely_similar(location['name'], get_location_field(import_config, 'name', values)):
		return False

	phone_number1 = strip_to_digits(get_location_field(import_config, 'phone_number', values))[-7:]
	phone_number2 = strip_to_digits(location['phone_number'])[-7:]
	if len(phone_number1) >= 7 and phone_number1 == phone_number2:
		return True

	return False


def simplify_name(name):
	if isinstance(name, unicode):
		name = unicodedata.normalize('NFKD', name).encode('ascii','ignore')
	punctuation_translator = string.maketrans(string.punctuation, ' ' * len(string.punctuation))
	name = re.sub(r'[^\x00-\x7f]', ' ', name) # remove all non-ASCII characters
	name = name.translate(punctuation_translator) # replace punctuation marks with spaces.
	name = re.sub(r'\s+', ' ', name)	# replace all double spaces with single space.
	return name.strip().lower()


def is_name_very_similar(name1, name2):
	name1 = simplify_name(name1)
	name2 = simplify_name(name2)
	return name1 == name2


def is_name_at_least_vaguely_similar(name1, name2):
	name1 = simplify_name(name1)
	name2 = simplify_name(name2)
	lcs_substring = lcs(name1, name2)
	return len(lcs_substring) > 5


def get_match_quality(import_config, location, values):
	values_longitude = float(get_location_field(import_config, 'longitude', values).strip())
	values_latitude = float(get_location_field(import_config, 'latitude', values).strip())
	location['longitude'] = float(location['longitude'])
	location['latitude'] = float(location['latitude'])
	values_name = get_location_field(import_config, 'name', values)
	# if not close enough, skip.
	distance = get_direct_distance(location['latitude'], location['longitude'],
		values_latitude, values_longitude)

	result = 0
	if ( distance < distance_threshold_km and
	is_name_very_similar(values_name, location['name']) ):
		result += 0.9
	if ( distance < distance_threshold_for_very_similar_information and 
	is_very_similar_information(import_config, values, location) ):
		result += 0.1

	return result


def get_id_of_matching_location(import_config, locations, values, location_duplicates):
	"""
	Tries to find a location matching the latitude and longitude closely and matching names.
	"""
	values_longitude = float(get_location_field(import_config, 'longitude', values).strip())
	values_latitude = float(get_location_field(import_config, 'latitude', values).strip())
	values_name = get_location_field(import_config, 'name', values).strip().lower()

	location_duplicates_with_same_name = [ld for ld in location_duplicates if ld['name'].strip().lower() == values_name]
	if len(location_duplicates_with_same_name) != 0:
		for location_duplicate in location_duplicates_with_same_name:
			location = [loc for loc in locations if loc['id'] == location_duplicate['location_id']][0]
			location['longitude'] = float(location['longitude'])
			location['latitude'] = float(location['latitude'])

			# if not close enough, skip.
			distance = get_direct_distance(location['latitude'], location['longitude'],
				values_latitude, values_longitude)
			if distance < distance_threshold_km_for_recorded_duplicate:
				return location_duplicate['location_id']
				# return the id of the location that this is a duplicate of

	likely_duplicates = []
	for location in locations:
		match_quality = get_match_quality(import_config, location, values)
		if match_quality > 0.01:
			likely_duplicates.append((location, match_quality))

	if len(likely_duplicates) == 0:
		return None
	else:
		# Sort by match quality so the best match goes to index 0.
		likely_duplicates.sort(key=lambda tup: tup[1], reverse=True)
		return likely_duplicates[0][0]['id']
