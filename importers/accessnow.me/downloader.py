import requests
import os.path
import json
import re


def get_digits(s):
	result = ''
	for c in s:
		if c in '0123456789':
			result += c
	return result


def sanitize_string(s):
	s_new = ''
	for c in s:
		if ord(c) < 15:
			s_new += ' '
		else:
			s_new += c

	s = s_new.strip()
	replacements = {
		u'\u2013': '-',
		u'\u2019': '"',
		u'\xc4': 'A',
		u'\xc5': 'A',
		u'\xc8': 'E',
		u'\xce': 'I',
		u'\xcf': 'I',
		u'\xdf': 'B',
		u'\xe4': 'a',
		u'\xe8': 'e',
		u'\xe9': 'e',
		u'\xea': 'e',
		u'\xee': 'i',
		u'\xef': 'i',
		u'\xf4': 'o',
		u'\xf6': 'o'
	}
	for key in replacements.keys():
		s = s.replace(key, replacements[key])
	return s


def download():
	cache_file_name = 'raw_data/places.json'
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			return f.read()

	url = 'https://classic.mapme.com/api/map/0f28e493-981f-4171-b3db-0217d48550f2/places'
	s = requests.get(url).content
	with open(cache_file_name, 'w') as f:
		f.write(s)
	return s


def get_tags_set(tags):
	return set([tag.lower() for tag in tags])


def get_phone_number_from(s):
	bucket_size = 20
	bucket = ''
	# Suggested at: https://stackoverflow.com/questions/3868753/find-phone-numbers-in-python-script
	phone_number_regex = re.compile("[\\(]?\\d{3}.?.?\\d{3}.?.?\\d{4}")
	for c in s:
		bucket += c
		matches = re.findall(phone_number_regex, bucket)
		if len(get_digits(bucket)) >= 9 and len(matches) != 0:
			return matches[0]

		if len(bucket) > bucket_size:
			bucket = bucket[1:] # remove the first character.


def get_web_url(place):
	for key in ['websiteURL', 'hiringPageURL']:
		if key in place and place[key]:
			return place[key]


def get_location(category, place):
	if 'companyTags' in place:
		tags = get_tags_set(place['companyTags'])
	else:
		tags = set([])
	return {
		'name': sanitize_string(place['companyName']),
		'address': sanitize_string(place['addressDisplay']),
		'longitude': place['lon'],
		'latitude': place['lat'],
		'phone_number': get_phone_number_from(place['description']),
		'external_url': get_web_url(place),
		'category': category,
		'has_accessible_parking': 'accessible parking' in tags,
		'has_braille': 'braille' in tags,
		'has_automatic_door': 'automatic door' in tags,
		'has_quiet_areas': 'quiet' in tags,
		'has_elevator': 'elevator' in tags,
		'has_accessible_washroom': 'accessible washroom' in tags,
		'has_ramp': 'ramp' in tags,
		'is_accomodation': 'hotel' in tags,
		'is_shopping': 'store' in tags,
		'is_healthcare': 'healthcare' in tags,
		'is_education': 'school' in tags,
		'is_restaurant': len(set(['bar', 'cafe', 'restaurant']).intersection(tags)) != 0
	}


def get_locations():
	content = download()
	data = json.loads(content)
	result = []
	for category in data['categories'].keys():
		tags = data['categories'][category]['tags']
		for tag_key in tags.keys():
			for place in tags[tag_key]['places']:
				if 'companyName' not in place:
					print('place = ' + json.dumps(place))
					exit()
				location = get_location(category, place)
				result.append(location)
	return result


if __name__=="__main__":
	download()