from model.toilet import Toilet
from lxml import html
from lxml.cssselect import CSSSelector
import os.path

def get_text_from_css(root, css_selector):
	child_element = root.cssselect(css_selector)
	if child_element:
		return child_element[0].xpath('string()').strip()
	else:
		return ''

def scrape_toilets(html_filename):
	"""
	Returns a list of Toilet instances pulled out of the specified HTML file
	"""
	result = []
	# load the file contents.
	with open(html_filename, 'r') as html_file:
		# read the file contents.
		content = html_file.read()
		# parse the HTML.
		tree = html.fromstring(content)
		# select the toilet elements.
		toilet_elements = tree.cssselect('.region-content .item-list ol > li')
		# loop through toilet elements.
		for toilet_element in toilet_elements:
			# get the various information needed for each toilet.
			name = get_text_from_css(toilet_element, '.views-field-title a')
			# every name seems to have the street address in it.
			# chop off the part after the first '@' to remove the street address.
			if '@' in name:
				name = name[:name.index('@')]
			total_logged_dumps = get_text_from_css(toilet_element, '.views-field-totalcount .field-content')
			street_address = get_text_from_css(toilet_element, '.street-address')
			postal_code = get_text_from_css(toilet_element, '.postal-code')
			country = get_text_from_css(toilet_element, '.country-name')
			region = get_text_from_css(toilet_element, '.region')
			locality = get_text_from_css(toilet_element, '.locality')
			phone_number = get_text_from_css(toilet_element, '.views-field-field-phone-number')
			toilet_paper_type = get_text_from_css(toilet_element, '.views-field-field-tp-availability a')
			
			# add new Toilet to result list.
			result.append(Toilet(name, total_logged_dumps, street_address, postal_code, 
				locality, country, phone_number, toilet_paper_type))
	return result

def scrape_all_html(print_progress):
	"""
	Processes all HTML files in the raw_html directory and returns a list of Toilet objects to represent the scraped information
	"""
	result = []
	raw_html_dir = 'raw_html'
	# loop through all files with the .html file extension.
	for filename in os.listdir(raw_html_dir):
		if print_progress:
			print 'Processing file ' + filename
		if filename.endswith(".html"):	
			result = result + scrape_toilets(raw_html_dir + '/' + filename)

	return result

if __name__ == '__main__':
	scrape_all_html(True)
