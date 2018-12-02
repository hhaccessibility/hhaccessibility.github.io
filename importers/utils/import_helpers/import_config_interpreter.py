import address_lookup


def get_location_field(import_config, field_name, values):
	"""
	Returns the value of the specified field by looking it up in the specified values.

	If longitude or latitude are requested but are not configured directly, 
	the address will be used to look up the corresponding coordinates.

	@param field_name is a string, the name of the field to look up.  For example, 'longitude'.
	@param values is a list expected to come from a line from a CSV file
	"""
	i = 0
	address = None
	for column in import_config['columns']:
		if 'location_field' in column:
			if column['location_field'] == field_name:
				return values[i]
			if column['location_field'] == 'address':
				address = values[i]

		i += 1
	if field_name in ['longitude', 'latitude'] and address:
		coords = address_lookup.get_coordinates(address)
		if field_name == 'longitude':
			return str(coords[0])
		else:
			return str(coords[1])

	return None
