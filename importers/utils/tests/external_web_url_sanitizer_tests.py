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
			('http://www.fiveguys.ca/', 'fiveguys.com'),
			('https://www.google.com/en-ca', 'google.com'),
			('https://www.google.com/en-us', 'google.com')
		]
		for test_case in test_cases:
			self.assertEquals(external_web_url_sanitizer.simplify_url(test_case[0]), test_case[1])

	def test_sanitize_google_redirect_urls(self):
		test_cases = [
			(None, None),
			('', ''),
			('http://www.fiveguys.ca/', 'http://www.fiveguys.ca/'),
			('https://www.google.ca/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0CB0QFjAAahUKEwjwwP_ng_DIAhVFmx4KHfEcC5s&url=http%3A%2F%2Fwww.luckyredshop.com%2F&usg=AFQjCNF7M5lYrwA6c4Eqmo5pu0xJXTSFZg&sig2=9BR9iXY4-kNejTB-6j9sUA&bvm=bv.106379543,d.dmo', 'http://www.luckyredshop.com/')
		]
		for test_case in test_cases:
			self.assertEquals(external_web_url_sanitizer.sanitize_google_redirect_urls(test_case[0]), test_case[1])


if __name__ == '__main__':
	unittest.main()
