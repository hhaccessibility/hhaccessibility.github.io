#!/home/liu1ee/apps/virtualenv/bin/python
from lxml import html
import requests
import os.path
import re
import csv
import urllib2
import logging
import time

def getlatlng(js):
   """
   a sample js string lookes like this
   #//debug stuff maphidden = 0;
       if (!maphidden) {
                createSearchMarker('20. Craft Heads Brewing Company','<label style="display:none">phone and address</label>89 University Avenue W<br/>Windsor, ON N9A 5N8<br/>Canada<br/>+1-226-246-3925','','ableroad.com/edit.php?index=20&amp;newID=craft-heads-brewing-company-windsor&amp;s=&amp;s1=windsor,ontario&amp;action=write',42.317252218728,-83.039797096553,20 - 1,1);
                     }
   """
   js = js.encode('ascii','ignore') # unicode to ascii
   reg = r'\([^)]+\)'
   m = re.findall(reg, js)
   return re.split(',\s*',m[1])[-4:-2]
def getname(s):
    if not len(s):
        return ''
    reg = r'\d+\.\s*(?P<name>(\w|\s)+)'
    m = re.match(reg,s[0])
    if not m:
        return ''
    return m.group('name')

def getcategory(s):
    if not len(s):
        return ''
    return re.split(r':\s*',s[0])[1]

def getdistance(s):
    if not len(s):
        return ''
    reg = r'(?P<dis>\d+\.?\d*)'
    m = re.search(reg,s[0])
    if not m:
        return ''
    return m.group('dis')

def getneighborhood(s):
    if not len(s):
        return ''
    return re.split(r':\s*',s[0])[1]

def getyelp(s):
    if not len(s):
        return ''
    reg = r'(?P<yelp>\d+\.?\d*)'
    m = re.search(reg,s[0])
    if not m:
        return ''
    return m.group('yelp')

def getstreet(s):
    if not len(s):
        return ''
    return s[0]

def getcity(s):
    if len(s) < 1:
        return ''
    s = ' '.join(s)
    m = re.search(r'(?P<city>[a-zA-Z]+),\s*', s)
    if not m:
        return ''
    return m.group('city')

def getstate(s):
    if not len(s):
        return ''
    s = ' '.join(s)
    m = re.search(r'(?P<city>[a-zA-Z]+),\s*(?P<state>[a-zA-Z]+)', s)
    if not m:
        return ''
    return m.group('state')

def getpostcode(s):
    if not len(s):
        return ''
    s = ' '.join(s)
    m = re.search(r'(?P<city>[a-zA-Z]+),\s*(?P<state>[a-zA-Z]+)\s+(?P<post>(\d{5,5})|(\w{3}\s\w{3}))', s)
    if not m:
        return ''.search(r'(?P<city>[a-zA-Z]+),\s*(?P<state>[a-zA-Z]+)\s+(?P<post>(\d{5,5})|(\w{3}\s\w{3}))', s)
    return m.group('post')

def getphone(s):
    if len(s) < 1:
        return ''
    if not re.search(r'\d{2,}-\d+',s[-1]):
        return ''
    return s[-1]

def extract_info(dombus):
    row = []
    name = dombus.xpath('.//a[@class="titlelink"]/text()')
    name = getname(name)
    row.append(name)

    category = dombus.xpath('.//div[@class="category"]/text()')
    category = getcategory(category)
    row.append(category)

    distance = dombus.xpath('.//div[@class="itemdistance"]/text()')
    distance = getdistance(distance)
    row.append(category)

    neighborhood = dombus.xpath('.//div[@class="neighborhood"]/text()')
    neighborhood = getneighborhood(neighborhood)
    row.append(neighborhood )

    yelprating = dombus.xpath('.//img[@class="yelprating"]/@alt')
    yelprating = getyelp(yelprating)
    row.append(yelprating)

    address = dombus.xpath('.//address/text()')

    street = getstreet(address)
    row.append(street)

    city = getcity(address)
    row.append(city)

    state = getstate(address)
    row.append(state)

    postcode = getpostcode(address)
    row.append(postcode)

    phone = getphone(address)
    row.append(phone)

    latlng = dombus.getprevious().text #lat lng is hidden in javascript
    latlng = getlatlng(latlng)
    row = row + latlng
    return row

def write_csv(rows):
    csv_filename = 'export.csv'
    if os.path.exists(csv_filename):
        csv_file = open(csv_filename,'a')
        writer = csv.writer(csv_file, delimiter=',',quotechar='"', quoting=csv.QUOTE_ALL)
    else:
        csv_file = open(csv_filename,'w')
        writer = csv.writer(csv_file, delimiter=',',quotechar='"', quoting=csv.QUOTE_ALL)
        title = ['name','category','distance','neighborhood','yelprating','street','city',
                'state','postcode','phone','lat','lng']
        writer.writerow(title)
    for row in rows:
        writer.writerow(row)
    csv_file.close()
    logging.info('csv file has been written!')

def download_if_not(url):
    pagecontent = ''
    page = requests.get(url)
    f = open('testhtml.html','w')
    f.write(page.content)
    f.close()
    pagecontent = page.content
    return pagecontent

def gen_url():
    locations = ['windsor, ontario']
    for location in locations:
            for category_id in range(2, 3):
                for page in range(0,5):
                    offset = page * 20
                    url = 'http://ableroad.com/search.php?s=&s1=' + urllib2.quote(location) + \
                            '&cat=' + str(category_id) + '&offset=' + str(offset) + '&action=search'
                    logging.info(url)
                    mainapp(url)
                    time.sleep(5)

def mainapp(url):
    dom = html.fromstring(download_if_not(url))
    businesses = dom.xpath('//div[@class="bigresultframe"]')
    rows = []
    for bus in businesses:
        row = extract_info(bus)
        rows.append(row)
    write_csv(rows)

def setuplogging():
    #logging setting
    logging.basicConfig(filename='importer.log',level=logging.DEBUG,\
            format='%(asctime)s - %(name)s - %(levelname)s - %(message)s', datefmt='%m/%d/%Y %I:%M:%S %p')

def main():
    setuplogging()
    gen_url()
main()
