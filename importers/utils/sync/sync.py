"""
sync is a function library for merging seed data into the database configured for the web application.

This is used for deploying imported data to app.accesslocator.com or demo.accesslocator.com.
"""
import MySQLdb
from import_helpers.seed_io import load_seed_data_from
import db_config


def get_db_connection():
	connection_settings = db_config.get_connection_settings()
	db = MySQLdb.connect(host=connection_settings['DB_HOST'],
                     user=connection_settings['DB_USERNAME'],
                     passwd=connection_settings['DB_PASSWORD'],
                     db=connection_settings['DB_DATABASE'])
	return db


def is_matching_location(location1, location2):
	if 'id' in location1 and 'id' in location2:
		return location1['id'] == location2['id']

	return (
		location1['latitude'] == location2['latitude'] and
		location1['longitude'] == location2['longitude']
		)


def is_matching_user(user1, user2):
	return user1['email'] == user2['email']


def is_matching_id(e1, e2):
	return e1['id'] == e2['id']


def find_match(table_name, data_list, element):
	if table_name == 'location':
		match_func = is_matching_location
	elif table_name == 'user':
		match_func = is_matching_user
	else:
		match_func = is_matching_id

	matches = [e for e in data_list if match_func(e, element)]
	if len(matches) > 1:
		raise ValueError('More than 1 match found. match count = ' + str(len(matches)))
	elif len(matches) == 1:
		return matches[0]
	else:
		return None


def run_query(db, sql):
	cur = db.cursor(MySQLdb.cursors.DictCursor)
	cur.execute(sql)
	db_data = [row for row in cur.fetchall()]
	return db_data


def add_missing_data(db, table_names):
	cursor = db.cursor()
	for table_name in table_names:
		json_data = load_seed_data_from(table_name)
		db_data = run_query(db, 'select id from ' + table_name)
		db_data = [row['id'] for row in db_data]
		new_data = [new_row for new_row in json_data if new_row['id'] not in db_data]
		if len(new_data) > 0:
			base_insert_sql = 'insert into `' + table_name + '`('
			for field_name in new_data[0].keys():
				base_insert_sql += '`' + field_name + '`,'

			base_insert_sql = base_insert_sql[:-1] + ') values(' # remove trailing comma.
			
			for new_row in new_data:
				values = []
				insert_sql = base_insert_sql
				for field_name in new_row.keys():
					values.append(new_row[field_name])
					insert_sql += '%s,'

				insert_sql = insert_sql[:-1] + ')' # remove trailing comma.
				print 'Table ' + table_name + ' Inserting ' + str(new_row['id'])
				print 'SQL: ' + insert_sql
				print 'values: ' + str(values)
				cursor.execute(insert_sql, values)
	db.commit()


def set_fields_on_location_tags(db):
	print 'setting fields on location_tags table'
	location_tags_data = load_seed_data_from('location_tag')
	cursor = db.cursor()
	for location_tag in location_tags_data:
		update_sql = 'update location_tag set icon_selector=%s where id=%s'
		cursor.execute(update_sql, (location_tag['icon_selector'], location_tag['id']))


def set_fields_on_locations(db):
	locations_data = load_seed_data_from('location')

	# We're only concerned with locations that have either address, phone number or both so 
	# let's filter out the useless data.
	# This may boost efficiency of the m*n time loop below by reducing m considerably.
	locations_data = [location for location in locations_data if location['address'] or location['phone_number']]

	fields = ['address', 'phone_number', 'external_web_url']
	location_query = 'select * from location where 0'
	for field in fields:
		location_query += ' or %s is null or %s=\'\'' % (field, field)

	cur = db.cursor(MySQLdb.cursors.DictCursor)
	cur.execute(location_query)
	db_data = [row for row in cur.fetchall()]
	print 'May update up to ' + str(len(db_data)) + ' records'
	cursor = db.cursor()
	for db_location in db_data:
		location = find_match('location', locations_data, db_location)
		if location:
			fields_to_set = []
			field_values = []
			for field in fields:
				if location[field] and not db_location[field]:
					fields_to_set.append(field)
					field_values.append(location[field])

			if len(field_values) > 0:
				update_sql = 'update location set '
				for field in fields_to_set:
					update_sql += field + '=%s,'
				
				update_sql = update_sql[:-1] # remove trailing comma.
				update_sql += ' where id=' + str(location['id'])
				print 'running: ' + update_sql
				cursor.execute(update_sql, field_values)
	db.commit()

if __name__ == 'main':
	set_fields_on_locations(get_db_connection())
