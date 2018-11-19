import unittest
from import_helpers import merging
from import_helpers import seed_io


class TestMerging(unittest.TestCase):
	def test_matches_true(self):
		true_values = ['yes', 'Yes', 'YES', 'y', 'TRUE', '1']
		false_values = ['no', 'No', 'NO', 'n', 'FALSE', '0']
		for true_value in true_values:
			self.assertTrue(merging.matches_true(true_value), true_value + ' should be a true value')
		for false_value in false_values:
			self.assertFalse(merging.matches_true(false_value), false_value + ' should be a false value')


if __name__ == '__main__':
	unittest.main()