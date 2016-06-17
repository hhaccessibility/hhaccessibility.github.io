"""
download_html.py downloads HTML that can then be scraped by scrape_html.py.
"""

import urllib2
import os.path

def download_html_for_page(location_number, skip_if_downloaded = True):
	"""
	download_html_for_page downloads HTML for the specified location number.
	"""
	output_file_name = 'raw_html/location_' + str(location_number) + '.html'
	result = True
	if skip_if_downloaded and os.path.isfile(output_file_name):
		return None

	url = 'http://www.toiletfinder.org/locations/' + str(location_number)
	try:
		response = urllib2.urlopen(url)
		html = response.read()
	except urllib2.HTTPError, e:
		html = "Could not download: " + str(e) + ", code: " + str(e.code)
		result = False

	with open(output_file_name, 'w') as html_file:
    		html_file.write(html)

	return result

def download_html(number_of_locations_to_download, print_progress = False, skip_if_downloaded = True):
	for location_number in range(1, number_of_locations_to_download):
		if print_progress:
			print ('Processing location ' + str(location_number) 
				+ ' of ' + str(number_of_locations_to_download) 
				)
		result = download_html_for_page(location_number, skip_if_downloaded)
		if print_progress:
			if result == False:
				print 'Download failed. Error message saved.'
			elif result == None:
				print 'Cache found'

def download_all():
	download_html(4510, True, True)

if __name__ == '__main__':
	download_all()
