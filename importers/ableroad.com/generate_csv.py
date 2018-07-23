from html_scraper import scrape_all_html
import csv
from download_html import download_all

def flexible_getattr(toilet, attr_name):
	if hasattr(toilet, attr_name):
		return str(getattr(toilet, attr_name))
	else:
		return ''

def generate_csv():
	print 'Collecting toilet information from html files.  This may take a few minutes.'
	toilets = scrape_all_html(True)
	print 'Generating CSV'
	filename = 'ableroad_data.csv'
	with open(filename, 'wb') as csv_file:
		writer = csv.writer(csv_file, delimiter=',',
                            quotechar='"', quoting=csv.QUOTE_ALL)
		extra_columns = [
			{'title': 'Name', 'key': 'name'}, {'title': 'Categories', 'key': 'categories'},
			{'title': 'Street Address', 'key': 'street_address'}, {'title': 'Zip Code', 'key': 'zipcode'},
			{'title': 'Neighbourhood', 'key': 'neighbourhood'}, {'title': 'Locality', 'key': 'locality'},
			{'title': 'State', 'key': 'state'}, {'title': 'Yelp Rating', 'key': 'yelp_rating'},
			{'title': 'Yelp Num Ratings', 'key': 'yelp_num_ratings'}, {'title': 'Yelp Reviews', 'key': 'yelp_review_start'},
			{'title': 'Distance', 'key': 'distance'}, {'title': 'Ableroad Rating', 'key': 'ableroad_rating'},
			{'title': 'Ableroad Num Ratings', 'key': 'ableroad_num_ratings'}, 
			{'title': 'Ableroad Reviews', 'key': 'ableroad_review_text'},
			{'title': 'Thumbnail URL', 'key': 'thumbnail_url'}, {'title': 'Details URL', 'key': 'details_url'},
			{'title': 'Longitude', 'key': 'longitude'}, {'title': 'Latitude', 'key': 'latitude'}
			]
		title_data = []

		for extra_column in extra_columns:
			title_data.append(extra_column['title'])

		writer.writerow(title_data)
		for toilet in toilets:
			row_data = []
			for column in extra_columns:
				row_data.append(flexible_getattr(toilet, column['key']))

			writer.writerow(row_data)
		print 'done writing file: ' + filename

if __name__ == '__main__':
	#download_all()
        generate_csv()
