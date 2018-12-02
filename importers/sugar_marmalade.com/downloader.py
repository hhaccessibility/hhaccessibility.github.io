import requests
import os.path
import lxml.html as html
import re


def download():
	cache_file_name = 'raw_html/contact.html'
	if os.path.isfile(cache_file_name):
		with open(cache_file_name, 'r') as f:
			content = f.read()
	else:
		url = 'http://www.sugarmarmalade.com/contact/'
		content = requests.get(url).content
		with open(cache_file_name, 'w') as f:
			f.write(content)
	return content


def sanitize_text(s):
	"""
	Removes non-ascii characters that can complicate character encoding and decoding unnecessarily.	
	Also, removes leading and trailing spaces, and doubled spaces because they serve no meaningful purpose to our needs.
	"""
	# Replace a dash that looks like a hyphen with a hyphen.
	s = re.sub(u'\uff0d', '-', s)
	s = re.sub(r'[^\x00-\x7F]+',' ', s)
	return re.sub('\s+', ' ', s.strip())


def get_location_as_dict(location_info_element):
	result = {}
	result['name'] = 'Sugar Marmalade - ' + sanitize_text(location_info_element.cssselect('h3')[0].text_content()).title()
	result['address'] = sanitize_text(location_info_element.cssselect('div.column:nth-child(2) > a')[0].text_content()).title()
	result['phone_number'] = sanitize_text(location_info_element.cssselect('div.column:nth-child(4)')[0].text_content())
	result['hours'] = sanitize_text(location_info_element.cssselect('div.column:last-child')[0].text_content())
	return result


def get_locations():
	content = download()
	document = html.fromstring(content)
	location_elements = document.cssselect('div.location-info')
	result = []
	for location_info_element in location_elements:
		result.append(get_location_as_dict(location_info_element))
	return result


if __name__ == '__main__':
	download()
