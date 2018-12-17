"""
db_io is a library of functions for talking with the web application's SQL database.

db_io essentially compliments the seed_io module which talks with the seed data files that are used by Laravel to seed the database.

These functions are useful for pulling information out of the SQL database and moving it into seed data.  
You may want to pull information out of SQL and into seed data if there's a need to manually interact 
with a lot of the data.

"""
import import_helpers.env_loader as env_loader
import MySQLdb
db = None


def get_db_connection():
	global db
	if db is None:
		connection_settings = env_loader.get_env_data()
		db = MySQLdb.connect(host=connection_settings['DB_HOST'],
						 user=connection_settings['DB_USERNAME'],
						 passwd=connection_settings['DB_PASSWORD'],
						 db=connection_settings['DB_DATABASE'])
	return db


def run_query(db, sql):
	cur = db.cursor(MySQLdb.cursors.DictCursor)
	cur.execute(sql)
	db_data = [row for row in cur.fetchall()]
	return db_data


def load_data_from(table_name):
	return run_query(get_db_connection(), 'select * from `' + table_name + '`')