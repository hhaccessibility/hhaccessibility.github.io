#!/usr/bin/python
# -*- coding: utf-8 -*-

import os
import lxml.html as html
import urllib.request, urllib.error, urllib.parse
import re


yelp_base_url = 'https://www.yelp.com/'


def filename_encode(s):
	"""
	Calculates a string similar to s but with any unsafe characters removed.
	Since slashes correspond with branching into a directory, they're replaced with underscore.
	"""
	s = " ".join(s.split()).strip()
	# remove any double whitespaces and trim leading or trailing spaces.

	# replace any non-alphanumeric characters with underscore.
	return re.sub('[^0-9a-zA-Z]+', '_', s)


def get_href_without_ad_redirect(href):
	start_token = 'redirect_url='
	if start_token in href:
		index = href.index(start_token)
		href = href[index + len(start_token):]
		if '&' in href:
			index = href.index('&')
			return urllib.parse.unquote(href[:index])
		else:
			return urllib.parse.unquote(href)

	return href


def get_location_name_from_url(url):
	url_ = url.split("/")
	if "biz" in url_:
		biz_position = url_.index("biz")
	else:
		biz_position = -1

	name = ''.join(url_[biz_position + 1:])
	if '?' in name:
		name = name[:name.index('?')]

	return name


def download_location_detail_page(url):
	"""
	Downloads a page from the specified URL and returns the
	file name it gets saved to.
	"""
	name = get_location_name_from_url(url)
	output_file_name = 'data/raw_html_'+ name +'.html'
	if not os.path.exists(output_file_name):
		print('Downloading details for location: ' + name)
		response = urllib.request.urlopen(url)
		html = response.read() # returns all the lines in a file.
		with open(output_file_name, 'wb') as html_file:
			html_file.write(html)
			html_file.close()
	else:
		print('Already have details for location: ' + name)

	return output_file_name


def download_location_detail_pages(index_page_html):
	# Get a list of location detail page URL's from the HTML.
	root = html.fromstring(index_page_html)
	location_links = root.cssselect('a.biz-name')
	for location_link in location_links:
		href_value = location_link.xpath('@href')[0]
		href_value = get_href_without_ad_redirect(href_value)
		if '/' in href_value and href_value[0] == '/':
			url = yelp_base_url + href_value
		else:
			url = href_value

		download_location_detail_page(url)


def auto_page_download(number_of_pages, find_query, location):
	"""
	Function will download every page based on number of pages, the
	description/query and locality. It will then create an offline copy of the page
	and store it in yelp.com/data folder. Parameter 'number_of_pages' signifies
	number of pages to be downloaded, 'find_query' is a based on user input,
	and 'location' signifies the location the user is currently targeting.

	@param number_of_pages is the number of index pages to download.
	@param find_query is keywords to search for a location
	@param location is the centre of the search.
	"""

	for i in range(number_of_pages):
		page_results = i * 10
		search_yelp = (yelp_base_url + 'search?find_desc=' + urllib.parse.quote(find_query) + '&find_loc='
			+ urllib.parse.quote(location))
		if page_results > 0:
			search_yelp = search_yelp + '&start=' + str(page_results)

		# yelp.com search parameter for location, and description is designed like search_yelp.
		# The search page is designed to show 10 results per page
		filename = "data/page_" + filename_encode(location) + "_" +  filename_encode(find_query) + "_{}.html".format(i)

		if not os.path.exists(filename):
			page = urllib.request.urlopen(search_yelp) # url specific to
			html = page.read() # returns all the lines in a file.
			print('Generating HTML file: ' + str(filename))
			with open(filename, 'wb') as html_file:
				html_file.write(html)
		else:
			with open(filename, 'r', encoding='utf-8') as f:
				html = f.read()
				f.close()

		download_location_detail_pages(html)


if __name__ == '__main__':
	auto_page_download(5, '', 'Windsor, ON, CA')
