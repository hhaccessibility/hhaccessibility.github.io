import utils
import os.path
import requests
import time
from lxml import objectify


def get_url(city, search_query):
	return 'https://www.yellowpages.ca/search/si-alph/1/' + search_query + '/' + city + '?format=georss'


def get_file_name(city, keywords):
	city = utils.sanitize_for_cache_file(city)
	keywords = utils.sanitize_for_cache_file(keywords)
	return 'raw_xml/page_' + city + '_' + keywords + '.xml'


def download_xml(city, keywords):
	cache_file_name = get_file_name(city, keywords)
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			return f.read()
	else:
		content = requests.get(get_url(city, keywords)).content
		print('writing file: ' + cache_file_name)
		with open(cache_file_name, 'w') as f:
			f.write(content)
		time.sleep(10)
		return content


def get_id_from_link_url(url):
	if '/' in url:
		url = url[url.rfind('/') + 1:]
		if '.html' in url:
			url = url[:-5] # chop off the trailing '.html'.
		return url


def get_location_dict_from_item(item_element):
	result = {}
	blank_keys = ['street_address', 'locality',
		'address_region', 'postal_code']
	for key in blank_keys:
		result[key] = ''

	ns = {
		'georss': 'http://www.georss.org/georss'
	}
	coords_s = str(item_element.xpath('.//georss:point', namespaces=ns)[0])
	coords = coords_s.split(' ')
	if len(coords) == 2:
		result['latitude'] = float(coords[0])
		result['longitude'] = float(coords[1])
	else:
		print('coords length is expected to be 2 but is %d, coords = %s' % (len(coords), coords_s))
		result['latitude'] = None
		result['longitude'] = None

	result['name'] = utils.sanitize_string(item_element.findtext('.//title').strip())
	address_str = item_element.findtext('.//description').strip()
	link = item_element.findtext('.//link').strip()
	result['external_id'] = get_id_from_link_url(link)
	parts = address_str.split(' ')
	result['phone_number'] = ''
	if len(parts) != 0 and len(utils.get_digits(parts[-1])) > 7:
		result['phone_number'] = utils.get_digits(parts[-1])
	return result


def get_locations_from(city, keywords):
	root = objectify.fromstring(download_xml(city, keywords))
	items = root.cssselect('item')
	results = []
	for item in items:
		location = get_location_dict_from_item(item)
		if location['latitude'] and location['longitude']:
			results.append(location)
	return results
