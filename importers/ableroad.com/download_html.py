"""
download_html.py downloads HTML that can then be scraped by scrape_html.py.
"""

import urllib2
import os.path

output_dir = 'raw_html'

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

def download_all():
	locations = ['', 'windsor, ontario']
	for location in locations:
		for category_id in range(2, 22):
			download_html(10, category_id, location, True, True)

if __name__ == '__main__':
	download_all()
