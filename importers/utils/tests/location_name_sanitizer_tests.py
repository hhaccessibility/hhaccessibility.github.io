import unittest
from import_helpers import seed_io
import import_helpers.location_name_sanitizer as location_name_sanitizer


class LocationNameSanitizerTests(unittest.TestCase):
	def test_is_mixed_case(self):
		unmixed_cases = ['mcdonalds', 'tim hortons', 'BINGO']
		mixed_cases = ['McDonalds', 'Tim Hortons']
		for unmixed_case in unmixed_cases:
			self.assertFalse(location_name_sanitizer.is_mixed_case(unmixed_case))
		for mixed_case in mixed_cases:
			self.assertTrue(location_name_sanitizer.is_mixed_case(mixed_case))

	def test_expand_all_saints(self):
		cases = [
			('', ''),
			('McDonalds', 'McDonalds'),
			('Forest Glade', 'Forest Glade'),
			('FOREST GLADE', 'FOREST GLADE'),
			('Storage', 'Storage'),
			('St. Luke Catholic School', 'Saint Luke Catholic School'),
			('St. Luke and st. Mary Catholic School', 'Saint Luke and Saint Mary Catholic School')
		]
		for test_case in cases:
			self.assertEqual(location_name_sanitizer.expand_all_saints(test_case[0]), test_case[1])

	def test_safe_expand_saints(self):
		cases = [
			('St. Clair', 'St. Clair'),
			('St. Mary Catholic Church', 'Saint Mary Catholic Church'),
			('St. Joseph\'s Cemetary', 'Saint Joseph\'s Cemetary'),
			('St. Luke and st. Mary Catholic School', 'Saint Luke and Saint Mary Catholic School')
		]
		for test_case in cases:
			self.assertEqual(location_name_sanitizer.safe_expand_saints(test_case[0]), test_case[1])

	def test_correct_case(self):
		cases = [
			('', ''),
			('McDonalds', 'McDonalds'),
			('mcdonalds', 'Mcdonalds'),
			('tim hortons', 'Tim Hortons'),
			('LCBO', 'LCBO'),
			('AT&T', 'AT&T'),
		]
		for test_case in cases:
			self.assertEqual(location_name_sanitizer.correct_case(test_case[0]), test_case[1])
	
	def test_get_word_replacement(self):
		cases = [
			('', ''),
			('Best', 'Best'),
			('Bset', 'Best'),
			('bset', 'Best')
		]
		for test_case in cases:
			self.assertEqual(location_name_sanitizer.get_word_replacement(test_case[0]), test_case[1])

	def test_correct_spelling(self):
		cases = [
			('', ''),
			('Best Western', 'Best Western'),
			('Bset Western', 'Best Western'),
			('bset Western', 'Best Western'),
			('bset,Western', 'Best,Western')
		]
		for test_case in cases:
			self.assertEqual(location_name_sanitizer.correct_spelling(test_case[0]), test_case[1])	


if __name__ == '__main__':
	unittest.main()