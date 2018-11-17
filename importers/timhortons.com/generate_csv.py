import downloader
import csv


def save_locations_to_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ["name", "longitude", "latitude", "address", "phone_number", "external_url"]

		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			writer.writerow(data)


if __name__=="__main__":
	locations = downloader.get_all_locations_from_stored_coordinates()
	save_locations_to_csv(locations, 'tim_hortons.csv')