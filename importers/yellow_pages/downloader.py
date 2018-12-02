import json
import string
import html_downloader
import xml_downloader
import utils


def get_search_config():
	with open('config/searches.json', 'r') as f:
		return json.load(f)


def find_matching_location(location, locations):
	# find match by looking for a very similar longitude and latitude.
	tolerance = 0.000001 # some tolerance for floating point error
	matches = [loc for loc in locations if abs(location['latitude'] - loc['latitude']) <= tolerance and 
		abs(location['longitude'] - loc['longitude']) <= tolerance]
	if len(matches) == 1:
		#print('match found by coordinate similarity')
		return matches[0]

	# Find match by having the same external_id.
	if location['external_id']:
		matching_external_ids = [loc for loc in locations if loc['external_id'] == location['external_id']]
		if len(matching_external_ids) == 1:
			print('match found by same external id')
			return matching_external_ids[0]


def merge(locations1, locations2):
	results = locations1[:]
	for loc in locations2:
		match = find_matching_location(loc, results)
		if match:
			for key in loc.keys():
				if (key not in match or not match[key]) and loc[key]:
					match[key] = loc[key]
		else:
			results.append(loc)
	return results


def process_all_searches():
	searches = get_search_config()
	results = []
	for city in searches['cities']:
		for keywords in searches['search_queries']:
			xml_downloader.download_xml(city, keywords)
			xml_locations = xml_downloader.get_locations_from(city, keywords)
			html_locations = []
			for letter in string.ascii_uppercase:
				content = html_downloader.download_locations_starting_with_letter(city, keywords, letter)
				new_html_locations = html_downloader.get_locations_from_html(content)
				html_locations = merge(html_locations, new_html_locations)

				# If nothing is found, give up immediately.
				# Although there may be more results with different letters,
				# we want to move on to save time.
				if 't find any business listings with the selected filters matching' in content:
					break

			new_locations = merge(xml_locations, html_locations)
			results = merge(results, new_locations)
			print('total locations = %d' % len(results))
	return results



if __name__ == '__main__':
	process_all_searches()
