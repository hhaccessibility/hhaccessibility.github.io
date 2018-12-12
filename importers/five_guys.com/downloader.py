import requests
import os.path
import json
import time
import re


def download(longitude, latitude, radius_m):
	"""
	Returns raw data from the five guys store location search API
	"""
	if not radius_m:
		radius_m = 25

	cache_file_name = 'raw_data/cache_%f_%f_%d_m.json' % (longitude, latitude, radius_m)
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			content = f.read()
	else:
		# http://www.fiveguys.com/5gapi/stores/ByDistance?lat=42.3149367&lng=-83.0363633&distance=25&secondaryDistance=250&lang=en-CA&units=M
		url = 'http://www.fiveguys.com/5gapi/stores/ByDistance?lat=%f&lng=%f&distance=%d&secondaryDistance=%d&lang=en-CA&units=M' % (latitude, longitude, radius_m, radius_m * 10)
		user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36'
		headers = {
			'User-Agent': user_agent
		}
		content = requests.get(url, headers = headers).content
		with open(cache_file_name, 'w') as f:
			f.write(content)

		# Delay when making a request so we don't burden the fiveguys website with too many requests in a short period of time.
		print('Downloaded data for ' + cache_file_name + ' and delaying to minimize burden on the fiveguys website')
		time.sleep(10)
	return json.loads(content)


def get_full_address(store_data):
	keys = ['AddressLine1', 'AddressLine2', 'City', 'StateOrProvince', 'PostalCode']
	result = ''
	for key in keys:
		if result:
			result += ' '
		if key in store_data and store_data[key]:
			result += store_data[key]

	result = re.sub( '\s+', ' ', result).strip()
	return result


def five_guys_to_simple_dict(store_data):
	if store_data['OnlineOrderUrl']:
		external_web_url = store_data['OnlineOrderUrl']['Url']
	else:
		external_web_url = None
	return {
		'name': 'Five Guys - ' + store_data['LocationName'],
		'id': store_data['Id'],
		'longitude': store_data['Longitude'],
		'latitude': store_data['Latitude'],
		'external_web_url': external_web_url,
		'hours': store_data['Hours'],
		'phone_number': store_data['PhoneNumber'],
		'postal_code': store_data['PostalCode'],
		'city': store_data['City'],
		'address': get_full_address(store_data),
		'has_delivery': store_data['HasDelivery']
	}


def get_locations(longitude, latitude, radius_m):
	data = download(longitude, latitude, radius_m)
	result = []
	for store in data:
		result.append(five_guys_to_simple_dict(store['Store']))
	return result


def get_coordinates_to_load():
	with open('coordinates.txt', 'r') as coordinates_file:
		content = coordinates_file.read()
		result = []
		for line in content.split("\n"):
			if ',' in line:
				comma_index = line.find(',')
				latitude = float(line[0:comma_index].strip())
				longitude = float(line[comma_index + 1:].strip())
				result.append((latitude, longitude))
		return result


class Location:
	def __init__(self, location_data):
		self.location_data = location_data
	
	def __hash(self):
		return self.location_data['id']


def get_locations_from_coordinates_file():
	result = set()
	for coordinate_pair in get_coordinates_to_load():
		locations = get_locations(coordinate_pair[1], coordinate_pair[0], 25)
		locations = set([Location(loc) for loc in locations])
		result = result.union(locations)

	return [loc.location_data for loc in result]


if __name__=="__main__":
	get_locations_from_coordinates_file()