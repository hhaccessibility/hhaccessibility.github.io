from model.toilet import Toilet
from lxml import html
from lxml.cssselect import CSSSelector
import os.path
import re
import string

def get_text_from_css(root, css_selector):
	child_element = root.cssselect(css_selector)
	if child_element:
		return child_element[0].xpath('string()').strip()
	else:
		return ''

def sanitize_integer(s):
	return re.sub("[^0-9]", "", s)

def sanitize_chars(s):
	"""
	sanitize_chars strips out non-ascii characters so the result is 
	more easily stored in CSV format
	"""
	printable = set(string.printable)
	return filter(lambda x: x in printable, s)

def chop_out_label(s):
	if ':' in s:
		return s[s.index(':') + 1:]
	else:
		return s

def find_facility(fac_element, image_src_ending):
	images = fac_element.cssselect('img')
	for image in images:
		src = image.get('src')
		if src.endswith(image_src_ending):
			return '1'
	
	return '0'

def get_nearby_toilet_ids(toilet_element):
	ul = toilet_element.cssselect('.loc_right > ul')
	result = []
	if ul:
		ul = ul[0]
		links = ul.cssselect('li > a')
		for link in links:
			href = link.get('href')
			if '/locations/' in href:
				result.append(href[href.index('/locations/') + len('/locations/'):])
		
	return result

def get_comments(toilet_element):
	comments = toilet_element.cssselect('.comment')
	result = []
	for comment in comments:
		result.append(sanitize_chars(comment.xpath('string()').strip()))

	return result

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
		toilet_elements = tree.cssselect('body')
		# loop through toilet elements.
		for toilet_element in toilet_elements:
			# get the various information needed for each toilet.
			name = get_text_from_css(toilet_element, '#container > h1')
			name = sanitize_chars(name)
			location_number = sanitize_integer(html_filename)
			rating_text = chop_out_label(get_text_from_css(toilet_element, '.loc_left > div > p:first-of-type'))
			rating_text = sanitize_chars(rating_text)
			average_rating = ''
			num_votes = ''
			if 'with' in rating_text:
				average_rating = rating_text[:rating_text.index('with')]
				num_votes = rating_text[rating_text.index('with') + len('with'):]
				num_votes = sanitize_integer(num_votes)
				average_rating = sanitize_integer(average_rating)

			gps_element = toilet_element.cssselect('.latlng')
			lat = get_text_from_css(gps_element[0], 'tr:nth-child(2) > td:first-child')
			longitude = get_text_from_css(gps_element[0], 'tr:nth-child(2) > td:nth-child(2)')
			fac_element = toilet_element.cssselect('.fac')
			if fac_element:
				fac_element = fac_element[0]
				is_for_men = find_facility(fac_element, 'male.png')
				is_for_women = find_facility(fac_element, 'female.png')
				is_for_disabled = find_facility(fac_element, 'disabled.png')
				is_for_baby_changing = find_facility(fac_element, 'baby.png')
				is_for_radar_key = find_facility(fac_element, 'radar.png')
			
			loc_right_paragraphs = toilet_element.cssselect('.loc_right > p')
			restrictions = loc_right_paragraphs[0].xpath('string()').strip()
			hours = loc_right_paragraphs[1].xpath('string()').strip()
			seasons = loc_right_paragraphs[2].xpath('string()').strip()
			cost = loc_right_paragraphs[3].xpath('string()').strip()
			added_by = loc_right_paragraphs[4].xpath('string()').strip()
			if 'added by' in added_by:
				added_by = added_by[
					added_by.index('added by') + len('added by'):]
				added_by = added_by.strip()

			nearby_toilet_ids = get_nearby_toilet_ids(toilet_element)
			restrictions = chop_out_label(restrictions)
			hours = chop_out_label(hours)
			seasons = chop_out_label(seasons)
			cost = chop_out_label(cost)
			hours = sanitize_chars(hours)
			seasons = sanitize_chars(seasons)
			restrictions = sanitize_chars(restrictions)
			cost = sanitize_chars(cost)
			added_by = sanitize_chars(added_by)
			comments = get_comments(toilet_element)

			result.append(Toilet(name, location_number, average_rating, num_votes, lat, 
				longitude, restrictions, hours, seasons, cost, added_by,
				is_for_men, is_for_women, is_for_disabled, is_for_baby_changing, is_for_radar_key, nearby_toilet_ids, comments))

	return result

def scrape_all_html(print_progress):
	"""
	Processes all HTML files in the raw_html directory and returns a list of Toilet objects to represent the scraped information
	"""
	result = set([])
	raw_html_dir = 'raw_html'
	count = 0
	# loop through all files with the .html file extension.
	for filename in os.listdir(raw_html_dir):
		if filename.endswith(".html"):	
			if print_progress and (count % 100 == 0):
				print 'Processing file ' + str(count) + ': ' + filename
			count = count + 1
			result |= set(scrape_toilets(raw_html_dir + '/' + filename))

	return result

if __name__ == '__main__':
	scrape_all_html(True)
