import unittest
from import_helpers import seed_io
from import_helpers.location_duplicate_container import LocationDuplicateContainer


class LocationDuplicateContainerTests(unittest.TestCase):
	def test_get_location_duplicates_by_name(self):
		duplicate_locations = LocationDuplicateContainer([])
		self.assertTrue(isinstance(duplicate_locations.get_location_duplicates_by_name('bla'), list))
		duplicate_locations = LocationDuplicateContainer(seed_io.load_seed_data_from('location_duplicate'))
		self.assertTrue(isinstance(duplicate_locations.get_location_duplicates_by_name('bla'), list))


if __name__ == '__main__':
	unittest.main()