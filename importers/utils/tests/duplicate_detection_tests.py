import unittest
from import_helpers import duplicate_detection
from import_helpers import seed_io
from import_helpers import merging


class TestDuplicateDetection(unittest.TestCase):
	def setUp(self):
		self.locations = [{
				"address": "4115 Walker Rd Bldg 4, Windsor, ON N8W 3T6",
				"data_source_id": 6,
				"external_web_url": None,
				"id": "00000000-0000-0000-0000-000000009353",
				"latitude": 42.2616358,
				"location_group_id": 99,
				"longitude": -82.970913,
				"name": "Dollarama - Walker Commons",
				"owner_user_id": None,
				"phone_number": "+1 519-972-2947",
				"universal_rating": None
			},
			{
				"address": "1555 Talbot Rd., Windsor, ON N9H 2N2",
				"data_source_id": 6,
				"external_web_url": None,
				"id": "00000000-0000-0000-0000-000000009274",
				"latitude": 42.2389977,
				"location_group_id": 99,
				"longitude": -83.0146537,
				"name": "Dollarama - Windsor Crossings Plaza",
				"owner_user_id": None,
				"phone_number": "+1 519-967-8210",
				"universal_rating": None
			},
		]

	def test_get_id_of_matching_location(self):
		import_config = {
			'columns': [
				{"location_field": "name"},
				{"location_field": "latitude"},
				{"location_field": "longitude"},
				{"location_field": "address"},
				{"location_field": "phone_number"}
			]
		}
		test_cases = [
			{
				'values': [
					'Dollarama - Walker Commons', '42.2616358', '-82.970913',
					'4115 Walker Rd Bldg 4, Windsor, ON N8W 3T6', '1-519-972-2947'],
				'expected_id': '00000000-0000-0000-0000-000000009353'
			}, {
				'values': [
					'Dollarama - Walker Commons', '42.2607697', '-82.9687173',
					'4115 Walker Rd Bldg 4, Windsor, ON N8W 3T6', '1-519-972-2947'],
				'expected_id': '00000000-0000-0000-0000-000000009353'
			}, {
				'values': [
					'Dollarama - Windsor Crossings Plaza', '42.2422401', '-83.0145262',
					'1555 Talbot Rd., Windsor, ON N9H 2N2', '1-519-967-8210'],
				'expected_id': '00000000-0000-0000-0000-000000009274'
			}
		]
		for test_case in test_cases:
			id = duplicate_detection.get_id_of_matching_location(import_config, self.locations, test_case['values'], [])
			self.assertEquals(id, test_case['expected_id'])

	def test_lcs(self):
		test_cases = [
			{
				'substrings': ('Tim Hortons', 'TimHortons'),
				'expected_result': 'Hortons'
			},
			{
				'substrings': ('Tim Hortons', ''),
				'expected_result': ''
			},
			{
				'substrings': ('Tim Hortons', 'McDonalds'),
				'expected_result': 'on'
			}
		]
		for test_case in test_cases:
			actual_result = duplicate_detection.lcs(test_case['substrings'][0], test_case['substrings'][1])
			self.assertEquals(test_case['expected_result'], actual_result)


	def test_is_very_similar_information(self):
		import_config = {
			'columns': [
				{"location_field": "name"},
				{"location_field": "phone_number"}
			]}
		location = merging.find_by_id(self.locations, '00000000-0000-0000-0000-000000009353')
		values = ['Dollarama', '+1 519-972-2947']
		self.assertTrue(duplicate_detection.is_very_similar_information(import_config, values, location))

	def test_simplify_name_smoke_test_on_all_location_names(self):
		locations = seed_io.load_seed_data_from('location')
		for location in locations:
			duplicate_detection.simplify_name(location['name'])

	def test_simplify_name(self):
		test_cases = [
			('Bob', 'bob'), ('Tim Hortons', 'tim hortons'),
			(' Tim Hortons ', 'tim hortons'), ('Tim  Hortons', 'tim hortons')]
		for test_case in test_cases:
			self.assertEquals(duplicate_detection.simplify_name(test_case[0]), test_case[1])

	def test_strip_to_digits(self):
		test_cases = [{'in': '(519) 123-1234', 'out': '5191231234'},
		{'in': '1 519 123 1234', 'out': '15191231234'},
		{'in': '', 'out': ''}]
		for test_case in test_cases:
			self.assertEquals(duplicate_detection.strip_to_digits(test_case['in']), test_case['out'])

	def test_is_name_very_similar(self):
		similar_names = [
			('McDonalds', ' mcdonalds'),
			('Harveys   Burger', 'Harveys Burger'),
			('dollarama - windsor crossings plaza', 'Dollarama - Windsor Crossings Plaza')]
		different_names = [('Tim Hortons', 'McDonalds')]
		for similar_name_pair in similar_names:
			self.assertTrue(duplicate_detection.is_name_very_similar(similar_name_pair[0], similar_name_pair[1]),
			similar_name_pair[0] + ' should be very similar to ' + similar_name_pair[1])
		for different_name_pair in different_names:
			self.assertFalse(duplicate_detection.is_name_very_similar(different_name_pair[0], different_name_pair[1]),
			different_name_pair[0] + ' should NOT be very similar to ' + different_name_pair[1])


if __name__ == '__main__':
	unittest.main()