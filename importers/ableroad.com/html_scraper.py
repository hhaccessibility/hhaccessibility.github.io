"""
html_scraper has functions for extracting useful information from files that were downloaded
"""
from model.toilet import Toilet
from lxml import html
from lxml.cssselect import CSSSelector
from download_html import output_dir
import os.path
import string
import re

def get_text_from_css(root, css_selector):
	child_element = root.cssselect(css_selector)
	if child_element:
		return sanitize_chars(child_element[0].xpath('string()').strip())
	else:
		return ''

def remove_label(s):
	if ':' in s:
		return s[s.index(':') + 1:].strip()
	else:
		return s

def sanitize_chars(s):
	"""
	sanitize_chars strips out non-ascii characters so the result is 
	more easily stored in CSV format
	"""
	printable = set(string.printable)
	return filter(lambda x: x in printable, s)

def decompose_locality_state_and_zip(s):
	"""
	Tries to extract locality, state, and zipcode out of s.
	"""
	zipcode = ''
	state = ''
	locality = ''
	if ',' in s:
		index = s.index(',')
		locality = s[:index].strip()
		state_and_zip = s[index + 1:].strip()
		if ' ' in state_and_zip:
			index = state_and_zip.index(' ')
			state = state_and_zip[:index].strip()
			zipcode = state_and_zip[index + 1:].strip()
		else:
			state = state_and_zip

	return {
		'zipcode': zipcode,
		'locality': locality,
		'state': state
	}

def find_index_of_locality_state_zip(lines):
	count = 0
	state_regex = re.compile('[A-Z]{2,}')

	for line in lines:
		if ',' in line and state_regex.search(line) and not ( '#' in line):
			return count

		count += 1

def decompose_address(toilet_element):
	street_address = ''
	phone_number = ''
	neighbourhood = ''
	lstate_z_info = {
		'zipcode': '',
		'locality': '',
		'state': ''
	}
	address_element = toilet_element.cssselect('address')[0]
	all_child_nodes = address_element.xpath('child::text()')
	lines = [str(t) for t in all_child_nodes]
	leng = len(lines)

	if leng == 0:
		print 'No address information available'
	elif leng == 1 and ',' in lines[0]:
		lstate_z_info = decompose_locality_state_and_zip(lines[0])
	else:
		street_address = lines[0].strip()
		index = find_index_of_locality_state_zip(lines)
		if index != None:
			locality_state_and_zip = lines[index]
		else:
			locality_state_and_zip = ''
		if leng > 3 and 1 != index:
			neighbourhood = lines[1].strip()

		if locality_state_and_zip:
			lstate_z_info = decompose_locality_state_and_zip(locality_state_and_zip)

		if leng > 2:
			phone_number = lines[leng - 1].strip()

	return {
		'street_address': street_address,
		'neighbourhood': neighbourhood,
		'locality': lstate_z_info['locality'],
		'state': lstate_z_info['state'],
		'zipcode': lstate_z_info['zipcode'],
		'phone_number': phone_number
	}

def get_yelp_rating(toilet_element):
	img_element = toilet_element.cssselect('img.yelprating')[0]
	src = img_element.get('src') # ie. ''
	# simplify the value.
	if '/' in src:
		index = src.rindex('/')
		src = src[index + 1:]

	# remove the file extension
	index = src.index('.')
	src = src[:index]
	# ie. src = 'stars_1' or src = 'stars_1_half'	
	src = src[len('stars_'):]
	add_half = 'half' in src
	if add_half:
		src = src[:len(src) - len('_half')]
	result = int(src)
	if add_half:
		rating = 0.5 + result
	else:
		rating = result

	num_ratings = 0
	num_ratings_text = get_text_from_css(toilet_element, 'span.reviews')
	if num_ratings_text:
		index = num_ratings_text.index(' ')
		num_ratings_text = num_ratings_text[:index].strip()
		num_ratings = int(num_ratings_text)

	return {
		'average_rating': rating,
		'num_ratings': num_ratings
	}

def get_ableroad_rating(toilet_element):
	rating = 0
	num_ratings = 0
	rating_text = get_text_from_css(toilet_element, '.rating-fixed')
	num_ratings_text = get_text_from_css(toilet_element, '.ourreviewcount').strip()
	if rating_text:
		index = rating_text.index(' ')
		rating = float(rating_text[:index].strip())

	if num_ratings_text:
		num_ratings_text = num_ratings_text[:-len('Review') - 1].strip()
		index = num_ratings_text.rindex(')')
		num_ratings = int(num_ratings_text[index + 1:].strip())
	
	return {
		'average_rating': rating,
		'num_ratings': num_ratings
	}

def get_thumbnail_url(toilet_element):
	img_element = toilet_element.cssselect('.media-avatar img')[0]
	return img_element.get('src')

def get_details_url(toilet_element):
	title_element = toilet_element.cssselect('a.titlelink')[0]
	return sanitize_chars(title_element.get('href'))

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
		toilet_elements = tree.cssselect('.bigresultframe')
		# loop through toilet elements.
		for toilet_element in toilet_elements:
			# get the various information needed for each toilet.
			name = get_text_from_css(toilet_element, 'a.titlelink')
			# every name seems to have the index in it.
			# chop off the part after the first '.' to remove the index.
			if '.' in name:
				name = name[name.index('.') + 1:].strip()

			address_info = decompose_address(toilet_element)
			categories = get_text_from_css(toilet_element, '.category')
			categories = remove_label(categories)
			yelp_review_start = get_text_from_css(toilet_element, '.yelpreviews').strip()
			if yelp_review_start.endswith('- Read More'):
				yelp_review_start = yelp_review_start[:-len('- Read More') - 1].strip()

			neighbourhood = remove_label(get_text_from_css(toilet_element, '.neighborhood'))
			distance = remove_label(get_text_from_css(toilet_element, '.itemdistance'))
			yelp_rating = get_yelp_rating(toilet_element)
			ableroad_rating = get_ableroad_rating(toilet_element)
			ableroad_review_text = get_text_from_css(toilet_element, '.ablereviewtext')
			thumbnail_url = get_thumbnail_url(toilet_element)
			details_url = get_details_url(toilet_element)

			if not address_info['neighbourhood']:
				address_info['neighbourhood'] = neighbourhood

			# add new Toilet to result list.
			result.append(Toilet(name, address_info['street_address'], address_info['neighbourhood'],
				address_info['locality'], address_info['state'], address_info['zipcode'],
				address_info['phone_number'], distance, categories, yelp_rating['average_rating'],
				yelp_rating['num_ratings'], yelp_review_start,
				ableroad_rating['average_rating'], ableroad_rating['num_ratings'], ableroad_review_text,
				thumbnail_url, details_url))

	return result

def scrape_all_html(print_progress):
	"""
	Processes all HTML files in the output directory and returns a list of Toilet objects to represent the scraped information
	"""
	result = set([])
	count = 0
	# loop through all files with the .html file extension.
	for filename in os.listdir(output_dir):
		if filename.endswith(".html"):
			if print_progress and (count % 10 == 0):
				print 'Processing file ' + str(count) + ': ' + filename
			count = count + 1
			result |= set(scrape_toilets(output_dir + '/' + filename))

	return result

if __name__ == '__main__':
	scrape_all_html(True)
