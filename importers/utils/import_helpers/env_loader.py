# Inline comments in a .env start with a '#' similar to Python.
# More discussion at: https://stackoverflow.com/questions/32368016/how-to-comment-in-laravel-env-file
def remove_inline_comment(line):
	if '#' in line:
		return line[:line.find('#')]
	else:
		return line


def get_env_data():
	env_filename = '../../app/.env'
	with open(env_filename, 'r') as env_f:
		content = env_f.readlines()
		lines = [remove_inline_comment(line).strip() for line in content]
		lines = [line for line in lines if '=' in line]
		key_values = {}
		for line in lines:
			index = line.find('=')
			key = line[:index].strip()
			value = line[index + 1:].strip()
			key_values[key] = value
		return key_values