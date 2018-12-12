import downloader
import csv


def save_locations_to_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ["name", "longitude", "latitude", "address", "phone_number", "external_url", "category"]
		if len(locations) > 0:
			for key in locations[0].keys():
				if key not in fieldnames:
					fieldnames.append(key)

		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			try:
				writer.writerow(data)
			except:
				print('trouble encoding a row')


if __name__=="__main__":
	locations = downloader.get_locations()
	save_locations_to_csv(locations, 'accessnow_me.csv')