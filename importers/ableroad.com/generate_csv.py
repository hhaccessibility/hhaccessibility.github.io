from html_scraper import scrape_all_html
import csv
from download_html import download_all

def generate_csv():
	print 'Collecting toilet information from html files.  This may take a few minutes.'
	toilets = scrape_all_html(True)
	print 'Generating CSV'
	filename = 'ableroad_data.csv'
	with open(filename, 'wb') as csv_file:
		writer = csv.writer(csv_file, delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		writer.writerow(['Name', 'Categories','Street Address', 'Zip Code',
			'Neighbourhood', 'Locality', 'State',
			'Yelp Rating', 'Num Yelp Ratings', 'Yelp Review Start', 'Distance',
			'Ableroad Rating', 'Ableroad Num Ratings', 'Ableroad Review Text', 'Thumbnail URL', 'Details URL'])
		for toilet in toilets:
			writer.writerow([toilet.name, toilet.categories, toilet.street_address,
				toilet.zipcode, toilet.neighbourhood, toilet.locality, toilet.state, toilet.yelp_rating
				, toilet.yelp_num_ratings, toilet.yelp_review_start, toilet.distance,
				toilet.ableroad_rating, toilet.ableroad_num_ratings, toilet.ableroad_review_text,
				toilet.thumbnail_url, toilet.details_url])
		print 'done writing file: ' + filename

if __name__ == '__main__':
	download_all()
        generate_csv()
