import downloader
import csv


def is_of_interest(location_data):
	required_values = ['name', 'longitude', 'latitude']
	for key in required_values:
		if not location_data[key]:
			return False
	
	return True


def save_locations_to_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ['name', 'longitude', 'latitude', 'external_web_url', 'phone_number',
			'address', 'street_address', 'address_region', 'locality', 'postal_code', 'external_id']
		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			if is_of_interest(data):
				try:
					writer.writerow(data)
				except:
					print('problem happened while writing a location to file. longitude=%f, latitude=%f' % (data['longitude'], data['latitude']))


if __name__=="__main__":
	locations = downloader.process_all_searches()
	save_locations_to_csv(locations, 'yellow_pages.csv')