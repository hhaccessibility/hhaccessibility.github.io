"""
Functions for reading information like API keys from the app/.env file.
"""
import os
import os.path as path

env_content = None


def get_env_file_path():
	result = os.path.dirname(os.path.realpath(__file__))
	result = path.abspath(path.join(result , "../.."))
	result += '\\app\\.env'
	return result


def remove_comment(line):
	if '#' not in line:
		return line
	else:
		return line[:line.index('#')]


def get_value_for(key):
	global env_content
	if env_content is None:
		with open(get_env_file_path(), 'r') as f:
			env_content = f.read()

	lines = env_content.split("\n")
	lines = [remove_comment(line) for line in lines]
	lines = [line for line in lines if key in line and '=' in line]
	if len(lines) != 1:
		raise ValueError('Unable to find setting for ' + key + ' in file')
	index = lines[0].index('=')
	return lines[0][index + 1:].strip()


def get_google_places_api_key():
	return get_value_for('GOOGLE_PLACES_API_KEY')