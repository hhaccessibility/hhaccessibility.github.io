import csv
import downloader
import json


def save_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ["name", "address", "phone_number", "hours"]

		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			writer.writerow(data)


if __name__=="__main__":
	save_csv(downloader.get_locations(), 'sugar_marmalades.csv')
