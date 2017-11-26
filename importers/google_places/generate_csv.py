from googleplaces import GooglePlaces, types, lang
import config_loader
import unicodecsv as csv
import location_tags
import address_fields
import sys
import time

google_places = GooglePlaces(config_loader.get_google_places_api_key())
keys = ['name', 'place_id', 'lat', 'long', 'local_phone_number', 
	'international_phone_number', 'rating', 'website', 'url']
keys.extend(location_tags.get_location_tag_keys())
keys.extend(address_fields.get_address_field_keys())


def place_to_dict(place):
	calculated_keys = ['lat', 'long']
	calculated_keys.extend(location_tags.get_location_tag_keys())
	calculated_keys.extend(address_fields.get_address_field_keys())
	result = {}
	result['lat'] = place.geo_location['lat']
	result['long'] = place.geo_location['lng']
	place.get_details()
	for key in location_tags.get_location_tag_keys():
		result[key] = location_tags.is_matching_location_tag(key, place)

	for key in address_fields.get_address_field_keys():
		result[key] = address_fields.get_address_field(key, place)

	for key in keys:
		if key not in calculated_keys:
			result[key] = getattr(place, key)

	return result


def write_csv_file(csv_filename, places):
	with open(csv_filename, 'wb') as csv_file:
		csv_writer = csv.DictWriter(csv_file, fieldnames=keys)
		csv_writer.writeheader()
		for place in places:
			if location_tags.is_of_interest(place):
				csv_writer.writerow(place_to_dict(place))


def remove_duplicate_places(places):
	place_ids = set([place.place_id for place in places])
	result = []
	for place_id in place_ids:
		result.append([p for p in places if p.place_id == place_id][0])

	return result


def get_place_ids_to_skip():
	with open('place_ids_to_skip.txt', 'r') as f:
		content = f.read()
		lines = content.split("\n")
		lines = [line.strip() for line in lines]
		return lines


def update_place_ids_file(places):
	place_ids_to_skip = get_place_ids_to_skip()
	new_place_ids = [place.place_id for place in places]
	place_ids_to_skip.extend(new_place_ids)
	with open('place_ids_to_skip.txt', 'w') as f:
		content = "\n".join(place_ids_to_skip)
		f.write(content)


def remove_place_ids_to_skip(places):	
	place_ids_to_skip = get_place_ids_to_skip()
	return [place for place in places if place.place_id not in place_ids_to_skip]


def get_places():
	results = []
	delta = 0.02
	for x in range(-1, 2):
		for y in range(-1, 2):
			lat = 42.2569622 + (delta * x)
			lng = -83.0112237 + (delta * y)
			print 'Requesting for lat=' + str(lat) + ', long=' + str(lng) 
			query_result = google_places.nearby_search(
				lat_lng={'lat': lat, 'lng': lng},
				radius=2000)
			results.extend(query_result.places)
			if query_result.has_next_page_token:
				print 'Will send another request for more results after short delay' 
				time.sleep(3)
				query_result_next_page = google_places.nearby_search(
					pagetoken=query_result.next_page_token)
				results.extend(query_result_next_page.places)
				query_request = query_result_next_page

	return remove_place_ids_to_skip(remove_duplicate_places(results))


if __name__ == '__main__':
	places = get_places()
	write_csv_file('windsor_places.csv', places)
	update_place_ids_file(places)
