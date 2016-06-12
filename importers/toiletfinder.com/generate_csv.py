from html_scraper import scrape_all_html
import csv

def generate_csv():
	print 'Collecting toilet information from html files.  This may take a few minutes.'
	toilets = scrape_all_html(False)
	print 'Generating CSV'
	filename = 'toiletfinder_data.csv'
	with open(filename, 'wb') as csv_file:
 		writer = csv.writer(csv_file, delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_MINIMAL)
		writer.writerow(['Name','Street Address', 'Postal Code', 
			'Locality', 'Country', 'Toilet Paper Type', 
			'Total logged dumps', 'Venue Category'])
		for toilet in toilets:
			writer.writerow([toilet.name, toilet.street_address, 
				toilet.postal_code, toilet.locality, toilet.country,
				toilet.toilet_paper_type, toilet.total_logged_dumps, 
				''
				])
		print 'done writing file: ' + filename

if __name__ == '__main__':
        generate_csv()

