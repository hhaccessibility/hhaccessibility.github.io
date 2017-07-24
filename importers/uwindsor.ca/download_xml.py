import lxml
from lxml import html, etree
import urllib.request


# Decides if we want to collect information on the specified type of location
# Types such as 'aEntrance' aren't of interest since they're parts of a larger building that we'd want to rate as a whole.
def is_location_type_of_interest(type):
	return type not in ['aEntrance', 'ebike', 'ephone', 'smoking']


# Returns a list of locations extracted from the specified XML file.
# Each location is represented by a dict with keys such as name,
#  longitude, latitude, type, and description.
def get_locations(file_name):
	locations = []
	with open(file_name) as xml_file:
		doc = etree.parse(xml_file)

	root = doc.getroot()

	for i in root:
		type = i.get('category')
		if is_location_type_of_interest(type):
			url = i.get('linkURL')

			# if only part of a URL given, include the full path.
			if not url.startswith('http'):
				url = 'http://web2.uwindsor.ca/pac/campusmap/' + url
			# encoding and decoding to utf-8 was required in order to avoid errors during csv creation
			locations.append({
				'name': i.get('name').encode("utf-8"),
				'latitude': i.get('lat').decode("utf-8"),
				'longitude': i.get('lng').decode("utf-8"),
				'type': type,
				'description': i.get('mtext').encode("utf-8"),
				'url': url
			})

	return locations

	
def download(file_name):
	response = urllib.request.urlopen('http://web2.uwindsor.ca/pac/campusmap/markers.xml')
	data = response.read()
	with open(file_name, 'wb') as outfile:
		outfile.write(data)


if __name__ == '__main__':
	download('building_markers.xml')