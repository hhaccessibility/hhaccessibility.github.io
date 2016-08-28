"""
download_html.py downloads HTML that can then be scraped by scrape_html.py.
"""

import urllib2
import os.path
from html_scraper import scrape_all_index_html
from config import output_dir

def download_details(url, skip_if_downloaded):
	"""
	download_details downloads HTML for the specified details page.

	Used to get detail pages off parsed index pages.
	"""

	# remove the hide parameter since it serves no purpose.
	if url.endswith('&hide=0'):
		index = url.index('&hide=0')
		url = url[:index]

	details_encoded = url

	# shorten details_encoded by removing uninformative substrings.
	if 'ableroad.com/detail.php?' in details_encoded:
		details_encoded = details_encoded[details_encoded.index('ableroad.com/detail.php') + len('ableroad.com/detail.php?'):]

	for removable_substring in ['&s1=&', '&s=&']:
		if removable_substring in details_encoded:
			index = details_encoded.index(removable_substring)
			details_encoded = details_encoded[:index] + details_encoded[index + len(removable_substring):]
	
	output_file_name = output_dir + "/details_" + details_encoded + ".html"
	if skip_if_downloaded and os.path.isfile(output_file_name):
		return

	if not os.path.isdir(output_dir):
		os.mkdir(output_dir)

	# download the file.
	response = urllib2.urlopen(url)
	html = response.read()
	with open(output_file_name, 'w') as html_file:
    		html_file.write(html)


def download_html_for_page(offset, category_id, location, skip_if_downloaded):
	"""
	download_html_for_page downloads HTML for the specified page.
	offset should be a number/integer with values from 1, 2...
	category_id should be a category number from 1, 2... 20
	"""
	output_file_name = output_dir + '/page_' + str(category_id) + '_' + str(offset)
	if location:
		output_file_name += '_' + location
 
	output_file_name += '.html'
	if skip_if_downloaded and os.path.isfile(output_file_name):
		return

	if not os.path.isdir(output_dir):
		os.mkdir(output_dir)

	url = 'http://ableroad.com/search.php?s=&s1=' + urllib2.quote(location) + '&cat=' + str(category_id) + '&offset=' + str(offset) + '&action=search'
	response = urllib2.urlopen(url)
	html = response.read()
	with open(output_file_name, 'w') as html_file:
    		html_file.write(html)

def download_html(number_of_pages_to_download, category_id, location, print_progress = False, skip_if_downloaded = True):
	number_locations_per_page = 20

	for page_number in range(number_of_pages_to_download):
		if print_progress:
			print ('Processing page ' + str(page_number) 
				+ ' of ' + str(number_of_pages_to_download) 
				+ ' in category ' + str(category_id)
				)
		offset = page_number * number_locations_per_page
		download_html_for_page(offset, category_id, location, skip_if_downloaded)

def download_detail_pages(skip_if_downloaded, show_progress):
	# parse everything.
	print 'Parsing all index HTML...'
	locations = scrape_all_index_html(False)
	# filter to just the ones with ableroad ratings.
	locations = [location for location in locations if location.ableroad_num_ratings > 0 and location.details_url]
	print 'Downloading details...'
	count = 0
	# download the details.
	for location in locations:
		if count % 10 == 0:
			print 'downloading details for ' + str(count) + ' of ' + str(len(locations))

		count += 1
		download_details(location.details_url, skip_if_downloaded)

def download_all():
	locations = ['', 'windsor, ontario']
	for location in locations:
		for category_id in range(2, 22):
			download_html(10, category_id, location, True, True)

	download_detail_pages(True, True)

if __name__ == '__main__':
	download_all()
