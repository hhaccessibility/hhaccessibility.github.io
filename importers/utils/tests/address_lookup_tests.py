import unittest
from import_helpers import address_lookup


class TestAddressLookup(unittest.TestCase):
	def test_get_cached_coordinates(self):
		self.assertTrue(isinstance(address_lookup.get_cached_coordinates(), dict))


if __name__ == '__main__':
	unittest.main()
