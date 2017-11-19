"""
db_config.py is responsible for making the database connection settings from 
the Laravel application's .env file.
"""

def get_connection_settings():
	env_file_path = '../../app/.env'
	keys = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME',
		'DB_PASSWORD']
	with open(env_file_path, 'r') as f:
		lines = f.readlines()
		# We don't care about leading or trailing spaces or lines not containing '='.
		lines = [line.strip() for line in lines if '=' in line]
		# Initialize to some defaults in case the .env doesn't specify them.
		result = {
			'DB_HOST': '127.0.0.1',
			'DB_PORT': 3306
		}
		for line in lines:
			index = line.index('=')
			key = line[:index].strip()
			if key in keys:
				new_val = line[index + 1:].strip()
				if key == 'DB_PORT':
					new_val = int(new_val)
				result[key] = new_val

		if len(result.keys()) != len(keys):
			raise ValueError('Some required keys not found in .env file. These include: ' + str(list(set(keys) - result.keys())))

		return result
