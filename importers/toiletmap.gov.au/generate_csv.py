from json_loader import load_all_json
from downloader import download_all
import csv

def generate_csv():
	print 'Collecting toilet information from JSON files.  This may take a few minutes.'
	toilets = load_all_json(True)
	print 'Generating CSV'
	filename = 'toiletmap_gov_au_data.csv'
	with open(filename, 'wb') as csv_file:
		writer = csv.writer(csv_file, delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writerow(['ID', 'Name','Latitude', 'Longitude',
			'Locality', 'State', 'TT', 'Address1', 'Address2', 'Hours', 'Sm', 'P', 'F', 'Date'])
		for toilet in toilets:
			writer.writerow([toilet.id, toilet.name, toilet.latitude, toilet.longitude, toilet.locality,
				toilet.state, toilet.tt, toilet.address1, toilet.address2, toilet.hours, toilet.sm, toilet.p,
				toilet.f, toilet.date
				])
		print 'done writing file: ' + filename

if __name__ == '__main__':
	print 'Downloading...'
	download_all(True, True)
	print 'Generating CSV...'
        generate_csv()

