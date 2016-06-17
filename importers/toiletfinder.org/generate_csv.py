from html_scraper import scrape_all_html
from download_html import download_all
import csv

def generate_csv():
	print 'Collecting toilet information from html files.  This may take a few minutes.'
	toilets = scrape_all_html(True)
	print 'Generating CSV with ' + str(len(toilets)) + ' toilets'
	filename = 'toiletfinder_org_data.csv'
	with open(filename, 'wb') as csv_file:
 		writer = csv.writer(csv_file, delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writerow(['ID', 'Name','lat', 'long', 
			'Rating', 'Num Votes', 'Male', 'Female', 'Disabled', 
			'Baby', 'Radar key', 'Hours', 'Restrictions', 'Seasons', 
			'Cost', 'Nearby toilet ids', 'Num Comments', 'Comments', 'Added by'])
		for toilet in toilets:
			writer.writerow([toilet.id, toilet.name, toilet.lat, 
				toilet.long, toilet.average_rating, toilet.num_votes,
				toilet.for_male, toilet.for_female, toilet.for_disabled, toilet.for_baby_changing, 
				toilet.for_radar_key, toilet.hours, toilet.restrictions, toilet.seasons, toilet.cost, 
				str(toilet.nearby_toilet_ids), str(len(toilet.comments)), toilet.comments,
				toilet.added_by
				])
		print 'done writing file: ' + filename

if __name__ == '__main__':
	download_all()
        generate_csv()

