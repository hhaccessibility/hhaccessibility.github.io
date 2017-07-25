
import csv
from download_xml import get_locations

def generate_csv():
	locations = get_locations('building_markers.xml')
	filename = 'building_accessibility_info.csv'
	keys = ['name', 'latitude', 'longitude', 'type', 'url', 'description',
		'is_education', 'is_restaurant', 'is_transportation', 'is_financial',
		'is_accommodation', 'is_sports']
	with open(filename, 'w') as csv_file:
		csv_writer = csv.DictWriter(csv_file, fieldnames=keys)
		csv_writer.writeheader()
		for location in locations:
			csv_writer.writerow(location)

if __name__ == '__main__':
	generate_csv()