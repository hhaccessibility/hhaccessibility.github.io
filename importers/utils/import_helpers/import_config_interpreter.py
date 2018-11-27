

def get_location_field(import_config, field_name, values):
	"""
	Returns the value of the specified field by looking it up in the specified values.

	@param field_name is a string, the name of the field to look up.  For example, 'longitude'.
	@param values is a list expected to come from a line from a CSV file
	"""
	i = 0
	for column in import_config['columns']:
		if 'location_field' in column and column['location_field'] == field_name:
			return values[i]
			
		i += 1

	return None
