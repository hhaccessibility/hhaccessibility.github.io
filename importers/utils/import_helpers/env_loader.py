def get_env_data():
	env_filename = '../../app/.env'
	with open(env_filename, 'r') as env_f:
		content = env_f.readlines()
		lines = [line.strip() for line in content if '=' in line]
		key_values = {}
		for line in lines:
			index = line.find('=')
			key = line[:index].strip()
			value = line[index + 1:].strip()
			key_values[key] = value
		return key_values