#!/usr/bin/python3

import requests
import sys
import time


def format_seconds(time_seconds):
	return '%dm %ds' % (time_seconds / 60, time_seconds % 60)


def populate_ratings_cache(site_url):
	num_fixed = 1
	populate_url = site_url + '/api/populate-ratings-cache'
	total_completed = 0
	original_total_remaining = 0
	
	# Fire a request before timing because it could process an 
	# unusually high number of locations that throw off the time estimations.
	start_time = time.time()
	while (num_fixed > 0):
		content_data = requests.post(populate_url, data = '').json()
		num_fixed = content_data['number_rated']
		remaining = content_data['number_unrated']
		if original_total_remaining == 0:
			original_total_remaining = remaining + num_fixed
		total_completed += num_fixed
		if original_total_remaining == 0:
			completion_ratio = 0
		else:
			completion_ratio = total_completed / original_total_remaining
		elapsed_time = time.time() - start_time
		if total_completed == 0:
			estimated_completion_time = 0
		else:
			estimated_completion_time = remaining * elapsed_time / total_completed
		print ('total processed: %d, number remaining = %d.  Estimated completion time: %s' % (total_completed, remaining, format_seconds(estimated_completion_time)))
		sys.stdout.flush()


def sanitize_site_url(site_url):
	if '://' not in site_url:
		site_url = 'http://' + site_url
	if site_url[-1] == '/':
		site_url = site_url[:-1]

	return site_url


if len(sys.argv) < 2:
	print('site url must be specified.')
	sys.exit(1)

site_url = sanitize_site_url(sys.argv[1])
if __name__ == '__main__':
	populate_ratings_cache(site_url)
	sys.exit(0)

