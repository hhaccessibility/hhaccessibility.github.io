import lxml.html as lh
from lxml import etree
import urllib2
import os
from lxml.cssselect import CSSSelector
import json

def yelp_page_downloader(url):
    url_ = url.split("/")
    # print url_
    if "biz" in url_:
        biz_position = url_.index("biz")
        # print biz_position
    output_file_name = 'data/raw_html_'+''.join(url_[biz_position + 1:])+'.html'
    if not os.path.exists(output_file_name):
        # print output_file_name
        response = urllib2.urlopen(url) #
        html = response.read() # returns all the lines in a file.
        # print 'Generating HTML file: ' + str(output_file_name)
        with open(output_file_name, 'w') as html_file:
                html_file.write(html)

    return output_file_name

def get_text_from_css(root, css_selector):
    child_element = root.cssselect(css_selector)
    if child_element:
        return child_element[0].xpath('string()').strip()
    else:
        return ''

def yelp_extract_location(input_html_file_name):
    with open(input_html_file_name, 'r') as html_file:
        content = html_file.read()
        root = lh.fromstring(content)
        coordinates = root.cssselect('.lightbox-map')[0].xpath('@data-map-state')[0] # xpath pulls attributes from given div
        print coordinates
        coordinates = json.loads(coordinates)
        latitude = coordinates['center']['latitude']
        print 'longitude = '
        print coordinates['center']['longitude']
        tag = get_text_from_css(root, 'div.short-def-list')
        # print tag

output_file_name = yelp_page_downloader('https://www.yelp.ca/biz/pho-orchid-toronto')
# yelp_extract(output_file_name)

# TO-DO :
# Create README.md file in data folder
# Get Address, latitude, longitude, description for access
# Automatically extract information from every page (all of the items on the page) - loop
# Builds list in area and then downloads
# Future - streamlining downloading






# sub_elements = etree.SubElement(root,"h3")
# tag = sub_elements.find(".//dd")
# tags = lh.get_element_by_id("short-def-list")
    # print tag
# for element in sub_elements.iter("dt"):
#     print element.tag
    # for elt in content:


#     blurb = new_html.xpath('//h3[text()="More business info"]/following-sibling::ul/text()')
# print blurb
# # for elt in new_html.iter('h3'):
#     text = elt.text_content()
#     if text.startswith('More business info'):
#         blurb = [text for node in elt.itersiblings('h3')
#                 for subnode in node.iter()
#                 for text in text_tail(subnode) if ]

        # html_parse = etree.parse(output_file_name)

# root = html_parse.getroot()
# div_of_interest = etree.SubElement(root, 'div')
# sub_div = etree.SubElement(div_of_interest,'h3')
# print sub_div
# # print div_of_interest
