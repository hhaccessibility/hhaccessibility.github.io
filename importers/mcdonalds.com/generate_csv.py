import csv
import downloader
import defaults


def save_csv(locations, csv_filename):
	with open(csv_filename, 'w') as csvfile:
		fieldnames = ["gbl_Number", "Name", "longitude", "latitude",
			"name_Type", "has_Wifi", "phone_Number",
			"address_Full", "postal_code", "city_Name", "province_Name", "country_Name",
			"has_Drive_Through", "parking", "twenty_four_hours", "outdoor_seating", "indoor_dining"]

		writer = csv.DictWriter(csvfile, fieldnames = fieldnames,  delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writeheader()
		for data in locations:
			writer.writerow(data)


if __name__=="__main__":
	latitude = defaults.latitude
	longitude = defaults.longitude
	radius = defaults.radius
	downloader.get_locations(latitude, longitude, radius)
	locations = downloader.get_all_cached_locations()

	save_csv(locations, 'mcdonalds.csv')
