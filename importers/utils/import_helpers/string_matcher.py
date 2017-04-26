import re
import os.path


def load_lines_from_file(filename):			
	with open(filename, 'r') as f:
		return f.readlines()


def single_space(s):
	while True:
		len1 = len(s)
		s = s.replace("  ", " ")
		if len(s) == len1:
			break

	return s


def strip_all(list1):
	return [single_space(s.strip().lower()) for s in list1]


def sanitize_regex(regex1):
	regex1 = regex1.strip('\n').strip('\r').lower()
	if '$' in regex1:
		regex1 = regex1.rstrip()
	if '^' in regex1:
		regex1 = regex1.lstrip()
	
	return regex1

	
def sanitize_all_regex(list1):
	return [sanitize_regex(regex1) for regex1 in list1]


def regex_matches_any(s, regex_list):
	for regex in regex_list:
		p = re.compile(regex)
		if p.search(s):
			return True
	
	return False


class StringMatcher():
	def __init__(self, path_prefix):
		filename = path_prefix + '_names.txt'
		self.names = []
		self.not_names = []
		self.name_regex = []
		self.not_name_regex = []
		if os.path.isfile(filename):
			self.names = strip_all(load_lines_from_file(filename))
		
		filename = path_prefix + '_name_regex.txt'
		if os.path.isfile(filename):
			self.name_regex = sanitize_all_regex(load_lines_from_file(filename))

		filename = path_prefix + '_not_name_regex.txt'
		if os.path.isfile(filename):
			self.not_name_regex = sanitize_all_regex(load_lines_from_file(filename))

		filename = path_prefix + '_not_names.txt'
		if os.path.isfile(filename):
			self.not_names = strip_all(load_lines_from_file(filename))

	def applies_to_name(self, location_name):
		location_name = single_space(location_name.strip().lower())
		if location_name in self.not_names:
			return False

		if location_name in self.names:
			return True

		if self.not_name_regex:
			if regex_matches_any(location_name, self.not_name_regex):
				return False

		if self.name_regex:
			if regex_matches_any(location_name, self.name_regex):
				return True

		return False