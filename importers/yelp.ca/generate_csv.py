import lxml.html as html
import os
import json
import itertools
from utils import get_text_from_css
import re
import unicodecsv as csv
# from collections import Counter


yelp_base_url = 'https://www.yelp.com'


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
        # xpath pulls attributes from within given div
        data = get_data_from(root)
        lightbox_div = root.cssselect('.lightbox-map')[0]
        if lightbox_div != "":
            coordinates = root.cssselect('.lightbox-map')[0].xpath('@data-map-state')[0]
            coordinates = json.loads(coordinates)
            latitude = coordinates['center']['latitude']
            longitude = coordinates['center']['longitude']
        else:
            latitude = 0
            longitude = 0
            # No coordinates found
        location_url = root.cssselect('.mapbox-map > a')[0].xpath('@href')[0]
        # gets map url of location
        if 'name' in data:
            biz_name = data['name']
            biz_name.encode("utf-8")
        else:
            biz_name = "-"
        if 'telephone' in data:
            phone_number = data['telephone']
        else:
            phone_number = 0
            # Indicates no phone number found
        if 'aggregateRating' in data:
            overall_rating = data['aggregateRating']['ratingValue']
        else:
            overall_rating = 0
            # Indicates no rating given
        if 'priceRange' in data:
            price_range = data['priceRange']
        else:
            price_range = 0
            # No price range found
        if 'address' in data:
            address = "{}, {}, {}, {}".format(data['address']['streetAddress'],data['address']['addressLocality'],data['address']['addressRegion'],data['address']['postalCode'])
        else:
            address = "No address found"
        # strips all whitespace from string and creates list
        if 'review' in data:
            review = data['review']
        else:
            review = ["No reviews found"]
        # looking for comments with information regarding accessibility incase wheelchair accessible is empty or absent.
        misc_info = ""
        wheelchair = ""
        accessible = ""
        wheelchair_comments = []
        comment_author = []
        accessible_comments = []
        for i in range(len(review)):
            reviews = review[i]['description']
            author = review[i]['author']
            # loops through all reviews
            # searches for keyword in reviews and returns list containing sentences containing keyword
            wheelchair = "".join(re.findall(r"([^.]*?wheelchair[^.]*\.)", reviews))
            wheelchair.encode("utf-8")
            accessible = "".join(re.findall(r"([^.]*?accessible[^.]*\.)", reviews))
            accessible.encode("utf-8")
            if wheelchair:
                wheelchair_comments.append("".join(wheelchair))
                comment_author.append(author)
            elif accessible:
                accessible_comments.append("".join(accessible))
                comment_author.append(author)

        # right-hand side column of page - more business info
        business_info = get_text_from_css(root, 'div.ywidget > ul')
        business_info = business_info.replace(" ", "").split('\n')
        filtered = []
        for i in business_info:
            if i != "":
                filtered.append(i)
        dict_business_info = dict(itertools.izip_longest(*[iter(filtered)] * 2, fillvalue = "" ))
        # retreiving accessibility information
        if 'WheelchairAccessible' in filtered:
            wheelchair_accessible = dict_business_info['WheelchairAccessible']
        elif wheelchair != "" and author != "":
            misc_info = "{} by {}".format(wheelchair_comments[0], author[0])
        elif accessible != "" and wheelchair == "":
            misc_info = "{} by {}".format(accessible_comments[0], author[0])
        else:
            wheelchair_accessible = "N/A"
            misc_info = "Call restaurant/store to verify accessibility."
        # checking for wifi info
        if "Wi-Fi" in filtered:
            wifi = dict_business_info['Wi-Fi']
        else:
            wifi = "N/A"
        # checking for parking info
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
            'price range': price_range,
            'overall rating': overall_rating,
            'misc info': misc_info
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


def generate_csv():
    print ("Gathering info..")
    locations = get_all_downloaded_locations()
    blank_row = {'name': "", 'address': "", 'latitude': "", 'longitude':"", 'wheelchair accessible':"",'phone number': "", 'parking': "", 'map': "", 'price range': "", 'overall rating': "", 'misc info': ""}
    keys = ['name', 'address', 'latitude', 'longitude', 'wheelchair accessible', 'wifi', 'phone number', 'parking', 'map', 'price range', 'overall rating', 'misc info']
    filename = 'locations.csv'
    print ("Creating file..")
    with open(filename, 'w') as csv_file:
        csv_writer = csv.DictWriter(csv_file, fieldnames=keys)
        csv_writer.writeheader()
        for location in locations:
            csv_writer.writerow(location)
            # csv_writer.writerow(blank_row)
            # adds empty row to separate store info
    print ("Done.")


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

