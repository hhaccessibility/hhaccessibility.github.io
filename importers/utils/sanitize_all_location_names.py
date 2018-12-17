import import_helpers.seed_io as seed_io
import import_helpers.location_name_sanitizer as location_name_sanitizer

locations = seed_io.load_seed_data_from('location')

for location in locations:
	location['name'] = location_name_sanitizer.sanitize_name(location['name'])

seed_io.write_seed_data('location', locations)