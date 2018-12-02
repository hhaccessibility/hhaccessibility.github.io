import requests
import re
import os.path
import urllib
import lxml.html as html
import time
import html_optimizer
import utils
import json


def get_url_for(city, keywords, letter):
	return 'https://www.yellowpages.ca/search/si-alph/1/' + keywords + '/' + city + '/rfl-' + letter


def get_cache_file_name(city, keywords, letter):
	city = utils.sanitize_for_cache_file(city)
	keywords = utils.sanitize_for_cache_file(keywords)
	return 'raw_html/page_' + city + '_' + keywords + '_' + letter + '.html'


def download_locations_starting_with_letter(city, keywords, letter):
	cache_file_name = get_cache_file_name(city, keywords, letter)
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			return f.read()
	else:
		content = requests.get(get_url_for(city, keywords, letter)).content
		with open(cache_file_name, 'w') as f:
			f.write(html_optimizer.optimize_html(content))
		time.sleep(10)
		return content


def get_coordinates_for(gis_data, location_id, pos_num, location_name):
	if location_id in gis_data:
		return gis_data[location_id]['coordinates']


def get_full_address_from(location):
	parts = ['street_address', 'locality', 'address_region']
	result = ''
	for part in parts:
		if result != '' and result[-2:] != ', ':
			result += ', '
		result += location[part]
	if location['postal_code']:
		if result:
			result += ' '
		result += location['postal_code']

	return result


def get_web_url_from(location_element):
	e = location_element.cssselect('.mlr__item--website a[href]')
	if e:
		e = e[0]
		href = e.xpath('@href')[0]
		token = 'redirect='
		if token in href:
			href = href[href.find(token) + len(token):]
			href = urllib.unquote(href)
			# remove trailing '/' since it probably serves no purpose.
			if href and href[-1] == '/':
				href = href[:-1]

			return href


def location_element_to_dict(location_element, gis_data):
	result = {}
	parts = {
		'name': '.listing__name a',
		'street_address': '[itemprop="streetAddress"]',
		'locality': '[itemprop="addressLocality"]',
		'address_region': '[itemprop="addressRegion"]',
		'postal_code': '[itemprop="postalCode"]',
		'phone_number': '.jsMapBubblePhone h4'
	}
	for key in parts:
		element = location_element.cssselect(parts[key])
		if len(element) != 0:
			result[key] = element[0].text_content()
		else:
			result[key] = ''

	result['external_web_url'] = get_web_url_from(location_element)
	result['name'] = utils.sanitize_string(result['name'])
	analytics_pin = json.loads(location_element.cssselect('[data-analytics-pin]')[0].xpath('@data-analytics-pin')[0])
	result['external_id'] = analytics_pin['lk_listing_id']
	coordinates = get_coordinates_for(gis_data, result['external_id'], analytics_pin['lk_pos_num'], result['name'])
	if coordinates is not None:
		result['latitude'] = coordinates[0]
		result['longitude'] = coordinates[1]
	else:
		result['latitude'] = None
		result['longitude'] = None
	result['address'] = get_full_address_from(result)
	return result


def get_geocoding_data_from(document):
	json_content = document.cssselect('.jsMapResource')[0].text_content()
	features = json.loads(json_content)['geoJson']
	if 'features' in features:
		features = features['features']
	result = {}
	for feature in features:
		properties = feature['properties']
		id = properties['id']
		name = properties['name']
		coordinates = feature['geometry']['coordinates']
		result[id] = {
			'name': name,
			'coordinates': coordinates
		}

	return result


def get_locations_from_html(html_content):
	document = html.fromstring(html_content)
	gis_data = get_geocoding_data_from(document)
	results = document.cssselect('.resultList')[0]
	location_elements = results.cssselect('.listing')
	result = []
	for location_element in location_elements:
		location = location_element_to_dict(location_element, gis_data)
		if location['latitude'] and location['longitude']:
			result.append(location)
	return result

