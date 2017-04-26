import json
from os.path import dirname
from os import sep
from import_helpers.string_matcher import StringMatcher


class StringMatcherRepo:
	def __init__(self, json_path):
		self.string_matchers = {}
		self.items_config = None
		self.json_path = json_path
		self.json_dir = dirname(self.json_path) + '/'

	def get_path_prefix_for(self, item_id):
		self.load_config()
		item = [t for t in self.items_config if t['id'] == item_id][0]
		return self.json_dir + item['prefix']
		
	def load_config(self):
		if self.items_config is None:
			with open(self.json_path, 'r') as f:
				self.items_config = json.load(f)

	def get_item_ids(self):
		self.load_config()
		return [item['id'] for item in self.items_config]
				
	def applies_to(self, s, item_id):
		if str(item_id) in self.string_matchers:
			s_matcher = self.string_matchers[str(item_id)]
		else:
			s_matcher = StringMatcher(self.get_path_prefix_for(item_id))
			self.string_matchers[str(item_id)] = s_matcher

		return s_matcher.applies_to_name(s)				