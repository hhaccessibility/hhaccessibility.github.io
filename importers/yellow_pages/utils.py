import re


def get_digits(s):
	result = ''
	for c in s:
		if c in '0123456789':
			result += c
	return result


def sanitize_for_cache_file(s):
	replace_with_spaces = ':;<>[]!@#$%^&*,.'
	for c in replace_with_spaces:
		s = s.replace(c, ' ')
	s = re.sub('\s+', ' ', s.strip())
	return s


def sanitize_string(s):
	s_new = ''
	for c in s:
		if ord(c) < 15:
			s_new += ' '
		else:
			s_new += c

	s = s_new.strip()
	replacements = {
		u'\u2013': '-',
		u'\u2019': '"',
		u'\xc4': 'A',
		u'\xc5': 'A',
		u'\xc8': 'E',
		u'\xce': 'I',
		u'\xcf': 'I',
		u'\xdf': 'B',
		u'\xe4': 'a',
		u'\xe8': 'e',
		u'\xe9': 'e',
		u'\xea': 'e',
		u'\xee': 'i',
		u'\xef': 'i',
		u'\xf4': 'o',
		u'\xf6': 'o'
	}
	for key in replacements.keys():
		s = s.replace(key, replacements[key])
	return s