#!/usr/bin/python
# -*- coding: utf-8 -*-
#To do: video download, fold name improment, url improvement
import os
import lxml.html as html
import urllib.request, urllib.error, urllib.parse
import re

url_img = 'https://www.yelp.com/biz_photos/burma-superstar-san-francisco-2?select=UaR8ZJlXHHWO3t-47tp0jg'
url_yelp = 'https://www.yelp.com/'
def save_imag(url, filename):
    page = urllib.request.urlopen(url)
    img_x = page.read()
    with open(filename, 'wb') as html_file:
        html_file.write(img_x);

def imag_save_url(url):
    page = urllib.request.urlopen(url)
    bhtml = page.read()
    root = html.fromstring(bhtml)
    Imag_urls = root.cssselect('img.photo-box-img')
    Imag_url = Imag_urls[0].xpath('@src')[0]
    folder = (root.xpath("//ul[@class='breadcrumbs']/li/a/text()"))[0]
    filename = '/' + urllib.parse.urlparse(Imag_url).path.split('/')[2] + os.path.splitext(Imag_url)[1];
    if not os.path.exists('data/'+ folder):
        os.makedirs('data/'+ folder)
    save_imag(Imag_url, 'data/'+ folder + filename)

def find_next_url(url):
    page = urllib.request.urlopen(url)
    bhtml = page.read()
    root = html.fromstring(bhtml)
    next_url = root.xpath("//a[contains(@class, 'js-media-nav_link--next')]/@href")
    return next_url
if __name__ == '__main__':

    imag_save_url(url_img)
    next_url = find_next_url(url_img)
    while(next_url):
        imag_save_url(url_yelp + next_url[0])
        next_url = find_next_url(url_yelp + next_url[0])

    #page = urllib.request.urlopen(url_img)
    #bhtml = page.read()
    #root = html.fromstring(bhtml)
    #next_url = root.xpath("//ul[@class='breadcrumbs']/li/a/text()")
    #print(next_url[0])