import unittest
from import_helpers import external_web_url_sanitizer


class TestExternalWebUrlSanitizer(unittest.TestCase):
	def test_use_dot_com(self):
		test_cases = [
			('google.com', 'google.com'),
			('www.google.com', 'www.google.com'),
			('http://www.google.com', 'www.google.com'),
			('https://www.google.com', 'www.google.com'),
			('http://www.google.com/', 'www.google.com/'),
			('http://www.google.ca', 'www.google.com'),
			('https://www.google.ca', 'www.google.com'),
			('http://www.google.ca/b', 'www.google.com/b'),
			('https://www.google.ca/b?y=1', 'www.google.com/b?y=1')
		]
		for test_case in test_cases:
			self.assertEquals(external_web_url_sanitizer.use_dot_com(test_case[0]), test_case[1])

	def test_simplify_url(self):
		test_cases = [
			('google.com', 'google.com'),
			('www.google.com', 'google.com'),
			('https://www.google.com', 'google.com'),
			("http://www.fiveguys.ca/", 'fiveguys.com'),
			('https://www.google.com/en-ca', 'google.com'),
			('https://www.google.com/en-us', 'google.com')
		]
		for test_case in test_cases:
			self.assertEquals(external_web_url_sanitizer.simplify_url(test_case[0]), test_case[1])


if __name__ == '__main__':
	unittest.main()
