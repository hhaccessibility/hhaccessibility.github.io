from file_loader import load_all
from downloader import download_all
import csv
import json

def flexible_getattr(toilet, attr_name):
	if hasattr(toilet, attr_name):
		return str(getattr(toilet, attr_name))
	else:
		return ''

def generate_csv():
	print 'Collecting toilet information from JSON files.  This may take a few minutes.'
	toilets = load_all(True)
	print 'Generating CSV'
	filename = 'toiletmap_gov_au_data.csv'
	with open(filename, 'wb') as csv_file:
		writer = csv.writer(csv_file, delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		# there are so many titles/columns that it is difficult to 
		# maintain separate lists.

		flat_titles = {
			'id': 'ID',
			'name': 'name',
			'latitude': 'Latitude',	
			'longitude': 'Longitude',
			'locality': 'Locality',
			'state': 'State',
			'tt': 'TT',
			'address1': 'Address1',
			'address2': 'Address2',
			'hours': 'Hours',
			'sm': 'Sm',
			'p': 'P',
			'facility_type': 'Facility Type',
			'is_service_station': 'Service Station',
			'is_park': 'Park',
			'is_park_or_reserve': 'Park or Reserve',
			'is_dump_point': 'Dump Point',
			'is_airport': 'Airport',
			'is_shopping_centre': 'Shopping Centre',
			'is_sporting_facility': 'Sporting Facility',
			'is_train_station': 'Train Station',
			'is_camping_ground': 'Camping Ground',
			'is_car_park': 'Car Park',
			'is_caravan_park': 'Caravan Park',
			'is_bus_station': 'Bus Station',
			'is_food_outlet': 'Food Outlet',
			'date': 'Date',
			'for_male': 'Male',
			'for_female': 'Female',
			'accessible_for_male': 'Male accessible',
			'accessible_for_female': 'Female accessible',
			'for_baby_changing': 'Baby Changing',
			'has_showers': 'Has Showers',
			'has_syringe_disposal': 'Has Syringe Disposal',
			'has_sanitary_disposal': 'Sanitary Disposal',
			'has_adult_change': 'Adult Change',
			'has_unisex': 'Unisex',
			'has_accessible_unisex': 'Accessible Unisex',
			'is_rh_transfer': 'RH Transfer',
			'is_lh_transfer': 'LH Transfer',
			'has_ambulant': 'Ambulant',
			'for_mlak_key': 'MLAK Key',
			'is_key_required': 'Key Required',
			'is_payment_required': 'Payment required',
			'is_access_limited': 'Access limited',
			'has_parking': 'Parking',
			'has_accessible_parking': 'Accessible Parking'
		}
		unflat_titles = [
			'Provided by name', 'Provided by email', 
			'Provided by url']
		titles = []
		for key, value in flat_titles.iteritems():
			titles.append(flat_titles[key])

		titles += unflat_titles
		writer.writerow(titles)

		for toilet in toilets:
			try:
				flat_data = []
				for key, value in flat_titles.iteritems():
					flat_data.append(flexible_getattr(toilet, key))

				row_data = flat_data + [
					toilet.provided_by['name'],
					toilet.provided_by['email'],
					toilet.provided_by['url']
				]
				assert len(titles) == len(row_data), 'titles len = ' + str(len(titles)) + ", data len = " + str(len(row_data))
				writer.writerow(row_data)
			except UnicodeEncodeError, e:
				print 'error: ' + str(e)
				print 'provided_by url: ' + toilet.provided_by['url']
				print 'row_data: ' + json.dumps(row_data)
				quit()

		print 'done writing file: ' + filename

if __name__ == '__main__':
	print 'Downloading...'
	#download_all(True, True)
	print 'Generating CSV...'
        generate_csv()
