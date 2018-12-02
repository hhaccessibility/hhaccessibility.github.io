import unittest
from import_helpers import seed_io


def get_key_from_list(list1, key):
	return set([element[key] for element in list1])


class TestSeedData(unittest.TestCase):
	"""
	These tests are useful for maintaining our seed data.
	"""
	def check_foreign_id(self, list1, key, ids, table_name):
		for element in list1:
			if element[key] not in ids:
				self.fail('Foreign key ' + key + ' ' + element[key] + ' in table ' + table_name + ' is unmatched.')

	def test_location_references(self):
		user_answers = seed_io.load_seed_data_from('user_answer')
		location_location_tags = seed_io.load_seed_data_from('location_location_tag')
		review_comments = seed_io.load_seed_data_from('review_comment')
		locations = seed_io.load_seed_data_from('location')
		location_ids = get_key_from_list(locations, 'id')
		self.check_foreign_id(user_answers, 'location_id', location_ids, 'user_answer')
		self.check_foreign_id(location_location_tags, 'location_id', location_ids, 'location_location_tag')
		self.check_foreign_id(review_comments, 'location_id', location_ids, 'review_comment')


if __name__ == '__main__':
	unittest.main()