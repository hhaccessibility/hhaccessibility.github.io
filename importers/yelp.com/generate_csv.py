import lxml.html as html
import os
import json
import itertools
from utils import get_text_from_css
import re
import unicodecsv as csv
from collections import Counter
import re


yelp_base_url = 'https://www.yelp.ca'


def get_data_from(root):
    json_data = get_text_from_css(root, 'script[type="application/ld+json"]')
    data = json.loads(json_data)
    return data


def get_location_info(location_details_filename):
    """
    get_location_info reads html file and returns a
    which contains information on the location
    """
    with open(location_details_filename, 'r') as html_file:
        content = html_file.read()
        root = html.fromstring(content)
        data = get_data_from(root)
        coordinates = root.cssselect('.lightbox-map')[0].xpath('@data-map-state')[0]
        # xpath pulls attributes from within given div
        coordinates = json.loads(coordinates)
        biz_name = get_text_from_css(root,'div.u-space-t1 > h1')
        phone_number = get_text_from_css(root,'span.biz-phone')
        latitude = coordinates['center']['latitude']
        longitude = coordinates['center']['longitude']
        location_url = root.cssselect('.mapbox-map > a')[0].xpath('@href')[0]
        # gets map url of location
        address = data['address']['streetAddress']
        overallRating = data['aggregateRating']['ratingValue']
        # strips all whitespace from string and creates list
        review = root.cssselect('.review-content > p')
        number_of_reviews = Counter(review) # retreives list with elements
        price_range = data['priceRange']
        misc_info = None
        wheelchair = None
        accessible = None
        for i in range(len(number_of_reviews)):
            reviews = review[i].xpath('string()')
            # loops through all reviews
            wheelchair = re.findall(r"([^.]*?wheelchair[^.]*\.)",reviews)
            # searches for keyword in reviews and returns list containing sentences containing keyword
            accessible = re.findall(r"([^.]*?accessible[^.]*\.)",reviews)

        business_info = get_text_from_css(root,'div.ywidget > ul')
        business_info = business_info.replace(" ","").split('\n')
        filtered = []
        for i in business_info:
            if i != "":
                filtered.append(i)
        dict_business_info = dict(itertools.izip_longest(*[iter(filtered)] * 2, fillvalue = "" ))
        # looking for comments with information regarding accessibility incase wheelchair accessible is empty or absent        if dict_business_info['WheelchairAccessible'] == "Yes":
        if 'WheelchairAccessible' in filtered:
            wheelchair_accessible = dict_business_info['WheelchairAccessible']
        elif wheelchair:
            misc_info = wheelchair[0]
        elif accessible:
            misc_info = accessible[0]
        else:
            wheelchair_accessible = "N/A"
            misc_info = "Call restaurant/store to verify accessibility."
        if "Wi-Fi" in filtered:
            wifi = dict_business_info['Wi-Fi']
        else:
            wifi = "N/A"
        if "Parking" in filtered:
            parking = dict_business_info['Parking']
        else:
            parking = "N/A"
        return {
            'name': biz_name,
            'address': address,
            'longitude': longitude,
            'latitude': latitude,
            'wheelchair accessible': wheelchair_accessible,
            'wifi': wifi,
            'phone number': phone_number,
            'parking': parking,
            'map': yelp_base_url + location_url,
            'overall rating': overallRating,
            'Misc Info': misc_info,
			'price range': price_range
            }


def get_all_downloaded_locations():
    """
    loops through directory and returns locations list with filenames
    that begin with raw_html
    """
    locations = []
    data_dir = 'data/'
    for (dirname, dirs, files) in os.walk('.'):
        for filename in files:
            if filename.startswith('raw_html'):
                filename = data_dir + filename
                location_details = get_location_info(filename)
                locations.append(location_details)
    return locations


def location_name_to_filename(location_name):
	location_name = re.sub('[^0-9a-zA-Z]+', '_', location_name)
	return location_name.encode('utf-8') + '.csv'


def generate_csv():
    print "Gathering info.."
    locations = get_all_downloaded_locations()
    keys = ['name','address','latitude','longitude','wheelchair accessible','wifi','phone number','parking', 'map', 'price range', 'overall rating', 'Misc Info']
    filename = 'locations.csv'
    with open(filename, 'w') as csv_file:
        csv_writer = csv.DictWriter(csv_file, fieldnames=keys)
        csv_writer.writeheader()
        for location in locations:
            csv_writer.writerow(location)


if __name__ == '__main__':
    generate_csv()


# TO-DO :
# Get Address, latitude, longitude, description for access - done
# Automatically extract information from every page (all of the items on the page) - loop - UPDATE - can loop to download every page as html
# Builds list in area and then downloads
# Future - streamlining downloading

# TO DO -
# Test download_full_page function - if page downloaded will contain 10 businesses and divs for every business.
# Rewrite yelp_biz_page_downloader in order to incorporate into auto_page_downloader

