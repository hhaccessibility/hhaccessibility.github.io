from lxml import html
import requests
import os.path
import re
import csv
import urllib2
import logging
import time
import string

#global variable
data_counter = 0

def get_text_from_css(root, css_selector):
    child_element = root.cssselect(css_selector)
    if child_element:
        return sanitize_chars(child_element[0].xpath('string()').strip())
    else:
        return ''

def sanitize_chars(s):
    """
    sanitize_chars strips out non-ascii characters so the result is
    more easily stored in CSV format
    """
    printable = set(string.printable)
    return filter(lambda x: x in printable, s)

def get_latlng(js):
   """
   a sample js string looks like this
   debug stuff maphidden = 0;
       if (!maphidden) {
                createSearchMarker('20. Craft Heads Brewing Company', '<label style="display:none">phone and address</label>89 University Avenue W<br/>Windsor, ON N9A 5N8<br/>Canada<br/>+1-226-246-3925','','ableroad.com/edit.php?index=20&amp;newID=craft-heads-brewing-company-windsor&amp;s=&amp;s1=windsor,ontario&amp;action=write',42.317252218728,-83.039797096553,20 - 1,1);
                     }
   """
   js = js.encode('ascii', 'ignore') # unicode to ascii
   reg = r'\([^)]+\)'
   m = re.findall(reg, js)
   return re.split(',\s*', m[1])[-4:-2]
def get_name(s):
    if not s:
        return ''
    reg = r'\d+\.\s*(?P<name>(\w|\s)+)'
    m = re.search(reg, s)
    if not m:
        return ''
    return m.group('name')

def get_category(s):
    if not s:
        return ''
    return re.split(r':\s*', s)[1]

def get_neighborhood(s):
    if not s:
        return ''
    return re.split(r':\s*', s)[1]

def get_ableroad(s):
    if not s:
        return ''
    reg = r'(?P<ablerating>\d+\.?\d*)'
    m = re.search(reg, s)
    if not m:
        return ''
    return m.group('ablerating')

def get_yelp(root, css):
    child_node = root.cssselect(css)
    if not len(child_node):
        return ''
    s = child_node[0].get('alt')
    reg = r'(?P<yelp>\d+\.?\d*)'
    m = re.search(reg, s)
    if not m:
        return ''
    return m.group('yelp')

def get_street(root, css):
    child_node = root.cssselect(css)
    if not len(child_node):
        return ''
    address = child_node[0].xpath('text()')
    reg = r'\d+'
    m = re.match(reg, address[0])
    if not m:
        return ''
    return address[0]

def get_city(root, css):
    child_node = root.cssselect(css)
    if not len(child_node):
        return ''
    address = child_node[0].xpath('text()')
    s = ' '.join(address)
    m = re.search(r'(?P<city>[a-zA-Z]+),\s*', s)
    if not m:
        return ''
    return m.group('city')

def get_state(root, css):
    child_node = root.cssselect(css)
    if not len(child_node):
        return ''
    address = child_node[0].xpath('text()')
    s = ' '.join(address)
    m = re.search(r'(?P<city>[a-zA-Z]+),\s*(?P<state>[a-zA-Z]+)', s)
    if not m:
        return ''
    return m.group('state')

def get_postcode(root, css):
    child_node = root.cssselect(css)
    if not len(child_node):
        return ''
    address = child_node[0].xpath('text()')
    s = ' '.join(address)
    m = re.search(r'(?P<city>[a-zA-Z]+),\s*(?P<state>[a-zA-Z]+)\s+(?P<post>(\d{5,5})|(\w{3}\s\w{3}))', s)
    if not m:
        return ''
    return m.group('post')

def get_phone(root, css):
    child_node = root.cssselect(css)
    if not len(child_node):
        return ''
    s = child_node[0].xpath('text()')
    if not re.search(r'\d{2,}-\d+', s[-1]):
        return ''
    return s[-1]

def extract_info(dom_locations):
    row = []
    name = get_text_from_css(dom_locations,'a.titlelink')
    name = get_name(name)
    row.append(name)

    category = get_text_from_css(dom_locations,'div.category')
    category = get_category(category)
    row.append(category)

    neighborhood = get_text_from_css(dom_locations,'div.neighborhood')
    neighborhood = get_neighborhood(neighborhood)
    row.append(neighborhood )

    ableroadating = get_text_from_css(dom_locations,'div[class="visually-hidden startableft"]')
    ableroadating = get_ableroad(ableroadating)
    row.append(ableroadating)

    yelprating = get_yelp(dom_locations,'img[class="yelprating"]')
    row.append(yelprating)

    address = dom_locations.xpath('.//address/text()')

    street = get_street(dom_locations,'address')
    row.append(street)

    city = get_city(dom_locations,'address')
    row.append(city)

    state = get_state(dom_locations,'address')
    row.append(state)

    postcode = get_postcode(dom_locations,'address')
    row.append(postcode)

    phone =  get_phone(dom_locations,'address')
    row.append(phone)

    latlng = dom_locations.getprevious().text #lat lng is hidden in javascript
    latlng = get_latlng(latlng)
    row = row + latlng
    return row

def write_csv(rows):
    csv_filename = 'export.csv'
    if os.path.exists(csv_filename):
        csv_file = open(csv_filename, 'a')
        writer = csv.writer(csv_file, delimiter=',', quotechar='"', quoting=csv.QUOTE_ALL)
    else:
        csv_file = open(csv_filename, 'w')
        writer = csv.writer(csv_file, delimiter=',', quotechar='"', quoting=csv.QUOTE_ALL)
        title = ['name', 'category', 'neighborhood', 'ablerating', 'yelprating', 'street', 'city',
                'state', 'postcode', 'phone', 'lat', 'lng']
        writer.writerow(title)
    for row in rows:
        writer.writerow(row)
    csv_file.close()
    global data_counter
    data_counter += len(rows)
    print('parsed {0} locations, having {1} records'.format(len(rows), data_counter))
    logging.info('csv file has been written!')

def download_if_not(url, islocalfile):
    pagecontent = ''

    if islocalfile and os.path.exists('tmp.html'):
        f = open('tmp.html', 'r')
        pagecontent = f.read()
    else:
        page = requests.get(url)
        f = open('tmp.html', 'w')
        f.write(page.content)
        pagecontent = page.content

    f.close()
    return pagecontent

def generate_url(location):
    for category_id in range(2, 22):
        for page in range(0, 10):
            offset = page * 20
            url = 'http://ableroad.com/search.php?s=&s1=' + urllib2.quote(location) + \
                    '&cat=' + str(category_id) + '&offset=' + str(offset) + '&action=search'
            logging.info(url)
            print('retriving and downloading page {0}'.format(url))
            retrieve_and_generate_csv(url)
            time.sleep(5)

def retrieve_and_generate_csv(url, islocalfile=False):
    dom = html.fromstring(download_if_not(url, islocalfile))
    locations = dom.xpath('//div[@class="bigresultframe"]')
    rows = []
    for location in locations:
        row = extract_info(location)
        rows.append(row)
    write_csv(rows)

def setuplogging():
    #logging setting
    logging.basicConfig(filename='importer.log', level=logging.DEBUG,\
            format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', datefmt='%m/%d/%Y %I:%M:%S %p')

def test_download():
    url = 'http://ableroad.com/search.php?s=reds-kitchen-tavern&offset=0&s1=peabody+ma&searchBut=Search&cat=1&action=search'
    retrieve_and_generate_csv(url, True)

def main():
    setuplogging()
    locations = ['windsor, ontario']
    for location in locations:
        generate_url(location)
    #test_download()
if __name__ == '__main__':
    main()
