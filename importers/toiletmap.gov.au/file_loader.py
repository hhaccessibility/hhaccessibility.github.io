"""
file_loader serves a similar role to html_scraper in toiletfinder.com and toiletfinder.org.
file_loader is responsible for logic involved with getting out of JSON and HTML files into a list of Toilet instances.
"""
import json
import os
import string
from lxml import html
from lxml.cssselect import CSSSelector
from model.toilet import Toilet
from downloader import output_dir

def load_toilets_from_json(json_filename):
	"""
	Returns a list of Toilet instances pulled out of the specified JSON file
	"""
	result = []

	# ignore file if it is empty.
	if os.stat(json_filename).st_size == 0:
		return result

	# load the file contents.
	with open(json_filename, 'r') as json_file:
		tree = json.load(json_file)
		for toilet_data in tree['R']:
			id = toilet_data['ID']
			name = toilet_data['T']
			facility_type = toilet_data['F']
			p = toilet_data['P']
			a1 = toilet_data['A1']
			a2 = toilet_data['A2']
			tt = toilet_data['TT']
			date = toilet_data['D']
			sm = toilet_data['SM']
			latitude = toilet_data['LA']
			longitude = toilet_data['LO']
			s = toilet_data['S']
			hours = toilet_data['O']
			locality = toilet_data['L']
			st = toilet_data['ST']
			
        		new_toilet = Toilet(name, id, latitude, longitude, 
				locality, st, facility_type,
        			p, a1, a2, sm, tt, hours, date)
			result.append(new_toilet)

	return result

def sanitize_chars(s):
	"""
	sanitize_chars strips out non-ascii characters so the result is 
	more easily stored in CSV format
	"""
	printable = set(string.printable)
	return filter(lambda x: x in printable, s)

def get_text_from_css(root, css_selector):
	child_element = root.cssselect(css_selector)
	if child_element:
		return sanitize_chars(child_element[0].xpath('string()').strip())
	else:
		return ''

def get_id_from_filename(html_filename):
	"""
	Returns the toilet id from the specified html filename.
	For example, 
	get_id_from_filename("toilet_3.html") == "3"
	get_id_from_filename("toilet_31.html") == "31"
	get_id_from_filename("raw_data/toilet_31.html") == "31"
	"""
	return html_filename[ html_filename.rindex('_') + 1 : -len('.html') ]

def check_title(icon_elements, title):
	"""
	Used for collecting both accessibility and feature information

	Assumes title does not contain a quote character.
	"""
	for icon_element in icon_elements:
		if icon_element.cssselect('[title="' + title + '"]'):
			return True

	return False

def decompose_address(left_column):
	paragraph_elements = left_column.cssselect('p:not([id])')
	full_address = address_element = paragraph_elements[0].xpath('string()').strip()
	lines = full_address.split("\n")

	# a1 is text before 'br' element
	state = ''
	locality = ''
	if len(lines) < 2:
		a1 = ''
		second_text = lines[0].strip()
	else:
		a1 = lines[0].strip()
		second_text = lines[1].strip()

	# state is after comma.
	if ',' in second_text:
		comma_index = second_text.index(',')
		locality = second_text[:comma_index].strip()
		state = second_text[comma_index + 1:].strip()
	else:
		state = second_text.strip()
		
	return {
		'address1': sanitize_chars(a1),
		'locality': sanitize_chars(locality),
		'state': sanitize_chars(state)
	}

def get_hours(left_column):
	paragraphs = left_column.cssselect('p:not([id])')
	return paragraphs[1].xpath('string()')

def decompose_provided_by(left_column):
	# try to find the 'Provided by' element first.
	provided_by_header = left_column.cssselect('h4:last-of-type')[0]
	name = ''
	email = ''
	url = ''
	p = provided_by_header.getnext()
	while p is not None and p.tag == 'p':
		p_text = p.xpath('string()').strip()
		if ' ' in p_text and '@' in p_text:
			parts = p_text.split(' ')
			for part in parts:
				part = part.strip()
				if '@' in part:
					email = part
				elif ':/' in part:
					url = part
		elif '@' in p_text:
			email = p_text
		elif ':/' in p_text:
			url = p_text
		else:
			name = p_text

		p = p.getnext()

	return {
		'name': sanitize_chars(name),
		'url': sanitize_chars(url),
		'email': sanitize_chars(email)
	}

def load_toilet_from_html(html_filename):
	# if file is too short, just return None to indicate no information could be extracted.
	if os.stat(html_filename).st_size < 200:
		return None

	with open(html_filename, 'r') as html_file:
		# read the file contents.
		content = html_file.read()
		# parse the HTML.
		tree = html.fromstring(content)
		# select the toilet elements.
		id = int(get_id_from_filename(html_filename))
		left_column = tree.cssselect('#leftcolumn')[0]
		name = get_text_from_css(left_column, '#Title')
		address_info = decompose_address(left_column)
		hours = sanitize_chars(get_hours(left_column))
		icons = left_column.cssselect('.icons')

		facility_type = ''
		facility_elements = left_column.cssselect('#FacilityTypeListReadOnly img')
		if facility_elements:
			facility_type = facility_elements[0].get('title')

		provided_by_info = decompose_provided_by(left_column)
		provided_by_name = provided_by_info['name']
		provided_by_email = provided_by_info['email']
		provided_by_url = provided_by_info['url']
		latitude = float(left_column.cssselect('#Latitude')[0].get('value'))
		longitude = float(left_column.cssselect('#Longitude')[0].get('value'))
		p = ''
		address2 = ''
		sm = ''
		tt = ''
		date = ''

		new_toilet = Toilet(name, id, latitude, longitude, 
		address_info['locality'], address_info['state'], 
		facility_type, p, address_info['address1'], address2, 
		sm, tt, hours, date)

		new_toilet.provided_by = provided_by_info
		new_toilet.for_male = check_title(icons, 'Male')
		new_toilet.for_female = check_title(icons, 'Female')

		new_toilet.accessible_for_male = check_title(icons, 'Accessible male')
		new_toilet.accessible_for_female = check_title(icons, 'Accessible female')
		new_toilet.for_baby_changing = check_title(icons, 'Baby change')
		new_toilet.has_accessible_parking = check_title(icons, 'Parking accessible')
		new_toilet.has_drinking_water = check_title(icons, 'Drinking water')
		new_toilet.has_sanitary_disposal = check_title(icons, 'Sanitary disposal')
		new_toilet.has_showers = check_title(icons, 'Showers')
		new_toilet.has_syringe_disposal = check_title(icons, 'Sharps disposal')
		new_toilet.is_service_station = check_title(icons, 'Service station')
		new_toilet.has_unisex = check_title(icons, 'Unisex')
		new_toilet.has_accessible_unisex = check_title(icons, 'Accessible unisex')
		new_toilet.has_parking = check_title(icons, 'Parking')
		new_toilet.is_park = check_title(icons, 'Park')
		new_toilet.is_park_or_reserve = check_title(icons, 'Park or reserve')
		new_toilet.is_sporting_facility = check_title(icons, 'Sporting facility')
		new_toilet.is_train_station = check_title(icons, 'Train station')
		new_toilet.is_dump_point = check_title(icons, 'Dump point')
		new_toilet.is_car_park = check_title(icons, 'Car park')
		new_toilet.is_shopping_centre = check_title(icons, 'Shopping centre')
		new_toilet.is_camping_ground = check_title(icons, 'Camping ground')
		new_toilet.is_access_limited = check_title(icons, 'Access limited')
		new_toilet.is_rh_transfer = check_title(icons, 'RH transfer')
		new_toilet.is_lh_transfer = check_title(icons, 'LH transfer')
		new_toilet.has_ambulant = check_title(icons, 'Ambulant')
		new_toilet.for_mlak_key = check_title(icons, 'MLAK key')
		new_toilet.is_key_required = check_title(icons, 'Key required')
		new_toilet.is_payment_required = check_title(icons, 'Key required')
		new_toilet.has_adult_change = check_title(icons, 'Adult change')
		new_toilet.is_caravan_park = check_title(icons, 'Caravan park')
		new_toilet.is_food_outlet = check_title(icons, 'Food outlet')
		new_toilet.is_bus_station = check_title(icons, 'Bus station')
		new_toilet.is_airport = check_title(icons, 'Airport')

		return new_toilet

def load_all(printing_progress):
	result = set([])
	count = 0
	if printing_progress:
		print 'Processing JSON files...'
	
	# loop through all json files in output directory
	for filename in os.listdir(output_dir):
		if filename.endswith(".json"):
			if printing_progress:
				print 'Processing file ' + str(count) + ': ' + filename
			count = count + 1
			result |= set(load_toilets_from_json(output_dir + '/' + filename))

	if printing_progress:
		print 'Processing HTML files...'

	# loop through all json files in output directory
	for filename in os.listdir(output_dir):
		if filename.endswith(".html"):
			if printing_progress and count % 1000 == 0:
				print 'Processing file ' + str(count) + ': ' + filename
			count = count + 1
			new_toilet = load_toilet_from_html(output_dir + '/' + filename)
			# if a toilet could be extracted from the html file, merge or add it to result.
			if new_toilet:
				matching_toilets = set([new_toilet]) & result
				if matching_toilets:
					matching_toilet = list(matching_toilets)[0]
					result.remove(matching_toilet)
					#print 'merging toilet ' + str(matching_toilet.id)
					for attr in new_toilet.__dir__():
						#print 'attr = ' + attr + ', ' + ', value = ' + str(getattr(new_toilet, attr))
						if getattr(new_toilet, attr) != '':
							setattr(matching_toilet, attr, getattr(new_toilet, attr))

					result.add(matching_toilet)
				else:
					result.add(new_toilet)


	return result

if __name__ == '__main__':
        load_all(True)


