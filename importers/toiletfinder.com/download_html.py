"""
download_html.py downloads HTML that can then be scraped by scrape_html.py.
"""

import urllib2
import os.path

def download_html_for_page(page_number, category_name, skip_if_downloaded = True):
	"""
	download_html_for_page downloads HTML for the specified page number.
	page_number should be a number/integer with values from 1, 2...
	category_name should be something like 'cleanest-toilets', ''
	"""
	output_file_name = 'raw_html/' + category_name + '_page_' + str(page_number) + '.html'
	if skip_if_downloaded and os.path.isfile(output_file_name):
		return

	url = 'http://toiletfinder.com/' + category_name + '?page=' + str(page_number)
	response = urllib2.urlopen(url)
	html = response.read()
	with open(output_file_name, 'w') as html_file:
    		html_file.write(html)

def download_html(number_of_pages_to_download, category_name, print_progress = False, skip_if_downloaded = True):
	for page_number in range(1, number_of_pages_to_download):
		if print_progress:
			print ('Processing page ' + str(page_number) 
				+ ' of ' + str(number_of_pages_to_download) 
				+ ' in category ' + category_name
				)
		download_html_for_page(page_number, category_name, skip_if_downloaded)

if __name__ == '__main__':
	download_html(22, 'cleanest-toilets', True, True)
	download_html(538, 'dirtiest-toilets', True, True)
