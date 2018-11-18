import downloader
import csv


def save_locations_to_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ['name', 'name_with_place', 'lat', 'lng', 'address', 'city', 
		'state', 'zip', 'hours', 'phone']
		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			writer.writerow(data)


if __name__=="__main__":
	locations = downloader.get_all_locations_various_radii()
	save_locations_to_csv(locations, 'dollaramas.csv')