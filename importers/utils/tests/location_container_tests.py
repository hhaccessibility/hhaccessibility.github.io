import unittest
from import_helpers import seed_io
from import_helpers.location_container import LocationContainer


class LocationContainerTests(unittest.TestCase):

	def test_get_location_keys(self):
		locations = LocationContainer([])
		keys = locations.get_location_keys()
		self.assertTrue(isinstance(keys, list))
		for key in keys:
			self.assertTrue(isinstance(key, basestring))

	def test_is_empty(self):
		locations = LocationContainer([])
		self.assertTrue(locations.is_empty())
		locations.insert({
			'id': '123',
			'longitude': -83,
			'latitude': 41
		})
		self.assertFalse(locations.is_empty())

	def test_locations_near(self):
		locations = seed_io.load_seed_data_from('location')
		container = LocationContainer(locations)
		self.assertFalse(container.is_empty())

		# smoke test a couple methods.
		windsor = {
			'id': '123',
			'latitude': 42.3,
			'longitude': -83
		}
		index = container.get_bucket_index_from_latitude(windsor['latitude'])
		self.assertTrue(isinstance(index, int))
		results = list(container.locations_near(windsor['longitude'], windsor['latitude'], 0.5))
		self.assertTrue(len(results) > 0)
		container.insert(windsor)
		new_results = list(container.locations_near(windsor['longitude'], windsor['latitude'], 0.5))
		self.assertTrue(len(new_results) == len(results) + 1)
		
		# Test that get_location_by_id works.
		windsor_lookup = container.get_location_by_id('123')
		self.assertIsNotNone(windsor_lookup)
		self.assertEqual(windsor['latitude'], windsor_lookup['latitude'])
		self.assertEqual(windsor['longitude'], windsor_lookup['longitude'])
		self.assertFalse(container.is_empty())


if __name__ == '__main__':
	unittest.main()