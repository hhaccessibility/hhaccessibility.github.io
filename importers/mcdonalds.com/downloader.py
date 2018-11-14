import traceback
import requests
import urllib3
import defaults
import json
import os.path
import os
import string


def cached_download(latitude, longitude, radius):
	cache_file_name = 'raw_data/mcdonalds_%s_%s_%d.json' % (latitude, longitude, radius)
	print('cache_file_name = ' + cache_file_name)
	url = "https://www.mcdonalds.com/googleapps/GoogleRestaurantLocAction.do?method=searchLocation&latitude=%s&longitude=%s&radius=%d&maxResults=100&country=ca&language=en-ca"% (latitude, longitude, radius)
	print('downloading from ' + url)
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'rb') as cache_file:
			print('retrieved from cache')
			return json.load(cache_file)

	urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
	headers = {
		'accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'accept-Encoding': 'gzip, deflate, br',
		'accept-Language': 'en-US,en;q=0.5',
		'connection': 'keep-alive',
		'DNT': '1',
		'Host': 'www.mcdonalds.com',
		'upgrade-insecure-requests': '1',
		'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0',
	}
	response = requests.get(url, headers=headers, verify=False)
	response = response.json()
	with open(cache_file_name, 'w') as cache_file:
		json.dump(response, cache_file)
	
	return response


def mcdonalds_json_to_location_result(mcdonalds_json_data):
	locations = mcdonalds_json_data['features']

	resulting_locations = []

	#iterating through all stores
	for location in locations:
		properties = location['properties']
		geometry = location['geometry']
		gblnumber = properties["id"]
		name = properties["name"]
		telephone = properties["telephone"]
		addressline1 = properties["addressLine1"]
		addressline3 = properties["addressLine3"]
		addressline2 = properties["addressLine2"]
		addressline4 = properties["addressLine4"]
		postal_code = properties["postcode"]
		wifi = properties['wifi']
		drivethru = properties['driveThru']
		longitude = geometry['coordinates'][0]
		latitude = geometry['coordinates'][1]
		filters = properties['filterType']
		wifi = 'WIFI' in filters
		drivethru = 'DRIVETHRU' in filters
		parking_area = 'PARKINGAREA' in filters
		outdoor_seating = 'OUTDOORSEATING' in filters
		twenty_four_hours = 'TWENTYFOURHOURS' in filters
		indoor_dining = 'INDOORDINING' in filters
		full_address = string.capwords(addressline1 + ' ' + addressline2 + ' ' + addressline3)
		full_address = ' '.join(full_address.split())

		data = {
			"gbl_Number": gblnumber,
			"Name": "McDonalds",
			"longitude": longitude,
			"latitude": latitude,
			"name_Type": name,
			"has_Wifi": wifi,
			"phone_Number": telephone,
			"postal_code": postal_code,
			"address_Full": full_address,
			"city_Name": addressline3,
			"province_Name": addressline2,
			"country_Name": addressline4,
			"has_Drive_Through": drivethru,
			'parking': parking_area,
			'twenty_four_hours': twenty_four_hours,
			'outdoor_seating': outdoor_seating,
			'indoor_dining': indoor_dining
		}
		resulting_locations.append(data)
	return resulting_locations


def get_locations(latitude, longitude, radius):
	"""
	Locates McDonalds stores in the specified area

	@param latitude in degrees
	@param longitude in degrees
	@param radius is likely in kilometers.
	"""
	return mcdonalds_json_to_location_result(cached_download(latitude, longitude, radius))


def get_all_cached_locations():
	locations = {}
	for file in os.listdir("raw_data"):
		if file.endswith(".json"):
			with open('raw_data/' + file, 'r') as f:
				locations_from_file = mcdonalds_json_to_location_result(json.load(f))
				for location in locations_from_file:
					locations[location['gbl_Number']] = location

	distinct_locations = []
	for id, location in locations.items():
		distinct_locations.append(location)
	
	print('There are %d locations.' % len(distinct_locations))
	return distinct_locations


if __name__=="__main__":
	latitude = defaults.latitude
	longitude = defaults.longitude
	radius = 25
	get_locations(latitude, longitude, radius)