import re
import json

spell_corrections = None
saints = None

def get_spell_corrections():
	global spell_corrections
	if spell_corrections is None:
		with open('data/location_names/spell_corrections.json', 'r') as f:
			spell_corrections = json.load(f)
	return spell_corrections


def get_saints():
	global saints
	if saints is None:
		with open('data/location_names/saints.txt', 'r') as f:
			saints = [line.strip() for line in f.read().split("\n")]
	return saints


def get_word_replacement(word):
	get_spell_corrections()
	lowercase_word = word.lower()
	if lowercase_word in spell_corrections:
		return spell_corrections[lowercase_word]
	else:
		return word


def correct_spelling(s):
	"""
	Replaces various incorrectly spelled words with the correct versions.
	"""
	result = ''
	current_word = ''
	# loop through words in s.
	for ch in s:
		if ch.isalpha():
			current_word += ch
		else:
			if current_word != '':
				result += get_word_replacement(current_word) + ch
				current_word = ''
			else:
				result += ch
	result += get_word_replacement(current_word)

	return result


def is_mixed_case(s):
	"""
	Determines if a mixture of upper and lower case characters are used in s.
	
	An ideal example of this "mixed case" would be something like "Tim Horton's" or "McDonalds".
	"""
	return s.lower() != s and s.upper() != s


def correct_case(s):
	"""
	Returns the string with a mixture of upper and lower case if that's not already the case unless the string is in an exceptional case such as LCBO.
	"""
	# Don't change case for website references.
	if is_website_reference(s.lower()):
		return s

	# Don't change case for names like 'LCBO', 'AT&T'.
	if len(s) < 5:
		return s
	if is_mixed_case(s):
		return s
	else:
		return s.title() # convert the first letter of every word to upper case.


def expand_all_saints(name):
	"""
	Replaces all abbreviations of 'Saint' with 'Saint'.
	"""
	saint_abbreviations = ['st', 'st.', 'St.', 'St', 'ST', 'ST.']
	for abbr in saint_abbreviations:
		abbr = abbr + ' '
		# Process Saint abbreviations at the start of the name.
		if abbr in name and name.find(abbr) == 0:
			name = 'Saint ' + name[len(abbr):]

		# Process Saint abbreviations later in the name.
		matches = re.findall('[^\\w]' + abbr.replace('.', '\\.'), name)
		for match in matches:
			name = name.replace(match, match[:1] + 'Saint ')
	return name


def is_website_reference(name):
	name = name.lower()
	common_top_level_domains = ['com', 'ca', 'org']
	for top_level_domain_name in common_top_level_domains:
		if '.' + top_level_domain_name in name or 'dot ' + top_level_domain_name in name:
			return True
	return False


def safe_expand_saints(name):
	"""
	We want to use the full word "Saint" in location names because that will be read more clearly by screen readers.
	"""
	lower_case_name = name.lower()

	# Don't change case for website references.
	if is_website_reference(lower_case_name):
		return name

	# Don't change case in cases where the 'st' represents 'street'.
	if 'church st.' in lower_case_name or 'church st ' in lower_case_name:
		return name

	if 'st ' in lower_case_name or 'st.' in lower_case_name:
		# If the name is related to a church, it is safe to replace all 'st.' abbreviations with 'Saint'.
		if len(set(['chapel', 'church', 'cemetary', 'cathedral', 'catholic', 'anglican', 'baptist']).intersection(set(re.sub("[^\w]", " ", lower_case_name).split(' ')))) != 0:
			return expand_all_saints(name)

		get_saints()
		for saint in saints:
			if saint in lower_case_name:
				return expand_all_saints(name)

	return name


def sanitize_name(name):
	name = name.strip()
	name = correct_spelling(name)
	name = safe_expand_saints(name)
	name = correct_case(name)
	return name