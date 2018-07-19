#!/usr/bin/python
# -*- coding: utf-8 -*-
#To do: video download, fold name improment, url improvement
import os
import lxml.html as html
import urllib.request, urllib.error, urllib.parse
import re
import time

url_img = 'biz_photos/burma-superstar-san-francisco-2?select_video=7sT6xHsZQAEP8H3pgwlhlg'
url_yelp = 'https://www.yelp.com/'
def save_image(url, filename):
    page = urllib.request.urlopen(url)
    img_x = page.read()
    with open(filename, 'wb') as html_file:
        html_file.write(img_x);

def image_save_url(url):
    page = urllib.request.urlopen(url)
    bhtml = page.read()
    root = html.fromstring(bhtml)
    Imag_urls = root.cssselect('img.photo-box-img')
    Imag_url = Imag_urls[0].xpath('@src')[0]
    folder = (root.xpath("//ul[@class='breadcrumbs']/li/a/text()"))[0]
    filename = '/' + urllib.parse.urlparse(Imag_url).path.split('/')[2] + os.path.splitext(Imag_url)[1];
    if not os.path.exists('data/'+ folder):
        os.makedirs('data/'+ folder)
    save_image(Imag_url, 'data/'+ folder + filename)
    

def find_next_url(url):
    page = urllib.request.urlopen(url)
    bhtml = page.read()
    root = html.fromstring(bhtml)
    next_url = root.xpath("//a[contains(@class, 'js-media-nav_link--next')]/@href")
    return next_url[0]

if __name__ == '__main__':
    next_url = url_img;
    query_dic = urllib.parse.parse_qs(urllib.parse.urlparse(next_url).query).keys()
    while 'video' in list(query_dic)[0]:
        next_url = find_next_url(url_yelp + next_url)
        query_dic = urllib.parse.parse_qs(urllib.parse.urlparse(next_url).query).keys()
    image_save_url(url_yelp+next_url)
    next_url = find_next_url(url_yelp+next_url)
    count = 0;
    while(next_url and count < 9):
        image_save_url(url_yelp + next_url)
        next_url = find_next_url(url_yelp + next_url)
        query_dic = urllib.parse.parse_qs(urllib.parse.urlparse(next_url).query).keys()
        while 'video' in list(query_dic)[0]:
            next_url = find_next_url(url_yelp + next_url)
            query_dic = urllib.parse.parse_qs(urllib.parse.urlparse(next_url).query).keys()
        count = count + 1
        time.sleep(0.3)