#!/usr/bin/python3

import requests
import sys
import time
import import_helpers.rating_cache_task_loader as rating_cache_task_loader
import json
import import_helpers.db_io


def run_query(db, sql):
	cur = db.cursor(MySQLdb.cursors.DictCursor)
	cur.execute(sql)
	db_data = [row for row in cur.fetchall()]
	return db_data


def clear_cache():
	connection = get_db_connection()
	run_query(connection, 'update location set ratings_cache=null, universal_rating=null')


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


def populate_root_location_group_ratings_cache(site_url):
	populate_url = site_url + '/api/populate-root-group-ratings-cache'
	result = requests.post(populate_url).json()


def populate_location_groups_ratings_cache(task_info):
	site_url = task_info['site_url']
	populate_root_location_group_ratings_cache(site_url)

	connection = get_db_connection(task_info)
	location_group_ids = run_query(connection, 'select id from location_group where id<>401')
	progress_index = 0
	# Get list of all location groups.
	for location_group_id in location_group_ids:
		location_group_id = location_group_id['id']
		data = {
			'location_group_id': location_group_id
		}
		populate_url = site_url + '/api/populate-group-ratings-cache'
		requests.post(populate_url, data)
		progress_index += 1
		if progress_index % 5 == 0:
			print ('processed location group: %d' % location_group_id)


if __name__ == '__main__':
	task_info = rating_cache_task_loader.get_task_info()
	print('Task info = ' + json.dumps(task_info, sort_keys=True, indent=4))
	if task_info['is_resetting_cache']:
		clear_cache()
	populate_ratings_cache(task_info['site_url'])
	populate_location_groups_ratings_cache(task_info)
	sys.exit(0)