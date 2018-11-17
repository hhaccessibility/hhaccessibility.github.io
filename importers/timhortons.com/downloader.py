import os.path
import requests
import lxml.html as html
try: from urlparse import urljoin # Python2
except ImportError: from urllib.parse import urljoin # Python3


base_url = 'https://locations.timhortons.com/search.html'


def get_text_from(element):
	if isinstance(element, list):
		result = ''
		for e in element:
			result += ' ' + get_text_from(e)
		return result
	else:
		return element.text_content()


def url_to_file_name(url):
	result = url
	parts_to_remove = ['timhortons.com', 'timhortons.ca']
	for part_to_remove in parts_to_remove:
		if part_to_remove in result:
			result = url[result.find(part_to_remove) + len(part_to_remove):]
	if result[0] == '/':
		result = result[1:]

	return result.replace('/', '_')


def get_location_details(details_url):
	cache_file_name = 'raw_html/' + url_to_file_name(details_url)
	content = None
	# Get from cache if it is available.
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			content = f.read()
	if not content:
		content = requests.get(details_url).content
		# Save cache.
		with open(cache_file_name, 'w') as f:
			f.write(content)
	document = html.fromstring(content)
	longitude = float(document.cssselect('meta[itemprop="longitude"]')[0].xpath('@content')[0])
	latitude = float(document.cssselect('meta[itemprop="latitude"]')[0].xpath('@content')[0])

	return {
		'longitude': longitude,
		'latitude': latitude
	}


def get_location_info_from_article_element(article_element):
	address = get_text_from(article_element.cssselect('.Teaser-address')).strip()
	phone_number = get_text_from(article_element.cssselect('.c-phone-main-number-span')).strip()
	external_url = article_element.cssselect('.Teaser-nearbyLink')[0].xpath('@href')[0].strip()
	external_url = urljoin(base_url, external_url)
	result = {
		'name': 'Tim Hortons',
		'address': address,
		'phone_number': phone_number,
		'external_url': external_url
	}
	details = get_location_details(external_url)
	result.update(details)

	return result


def get_locations(latitude, longitude):
	cache_file_name = 'raw_html/timhortons_%f_%f.html' % (latitude, longitude)
	content = None
	# Get from cache if it is available.
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			content = f.read()
	if not content:
		url = base_url + '?qp=&q=%f%%2C%f' % (latitude, longitude)
		content = requests.get(url).content
		# Save cache.
		with open(cache_file_name, 'w') as f:
			f.write(content)

	document = html.fromstring(content)
	location_articles = document.cssselect('.location-list-results > article.Teaser')
	result = []
	# loop through locations in content.
	for location_article in location_articles:
		location_info = get_location_info_from_article_element(location_article)
		result.append(location_info)
		
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


def get_with_offsets(coordinates, offset):
	new_coordinates = []
	for coordinate_pair in coordinates:
		for x in [-1, 1]:
			for y in [-1, 1]:
				new_coordinates.append( (coordinate_pair[0] + offset * x, coordinate_pair[1] + offset * y) )
		new_coordinates.append(coordinate_pair)
	return new_coordinates


def get_all_locations_from_stored_coordinates():
	coordinates = get_with_offsets(get_coordinates_to_load(), 0.05)
	locations = {}
	for coordinate_pair in coordinates:
		new_locations = get_locations(coordinate_pair[0], coordinate_pair[1])
		for new_location in new_locations:
			if new_location['external_url'] not in locations:
				locations[new_location['external_url']] = new_location
	
	locations = locations.values()
	return locations


if __name__=="__main__":
	get_all_locations_from_stored_coordinates()