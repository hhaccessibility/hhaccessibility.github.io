def get_address_component(place, type):
	ac = [ac for ac in place.details['address_components'] if type in ac['types']]
	if len(ac) == 0:
		return ''
	else:
		return ac[0]['long_name']


def get_address(place):
	street_number = get_address_component(place, 'street_number')
	route = get_address_component(place, 'route')
	city = get_address_component(place, 'administrative_area_level_2')
	region = get_address_component(place, 'administrative_area_level_2;')
	country = get_address_component(place, 'country')
	parts = [street_number, route, city, region, country]
	result = ''
	for part in parts:
		if part != '':
			result += ' ' + part
	
	return result.strip()


def get_postal_code(place):
	return get_address_component(place, 'postal_code')


def get_address_field_keys():
	return ['address', 'postal_code']


def get_address_field(key, place):
	if key == 'address':
		return get_address(place)
	else:
		return get_postal_code(place)