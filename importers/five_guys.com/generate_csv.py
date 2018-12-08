import csv
import downloader


def save_locations_to_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ['name', 'longitude', 'latitude', 'external_web_url', 'phone_number',
			'address', 'city', 'postal_code', 'hours', 'has_delivery', 'id']
		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			writer.writerow(data)


if __name__=="__main__":
	locations = downloader.get_locations_from_coordinates_file()
	save_locations_to_csv(locations, 'five_guys.csv')