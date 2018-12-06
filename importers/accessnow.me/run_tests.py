import unittest
import downloader


class TestDownloader(unittest.TestCase):
	def test_get_phone_number_from(self):
		test_cases = [
			('', None),
			('123-123-1234', '123-123-1234'),
			('1231231234', '1231231234'),
			('(123)123-1234', '(123)123-1234'),
			('(123) 123-1234', '(123) 123-1234'),
			('Hello 123-123-1234', '123-123-1234'),
			('Hello (123)123-1234', '(123)123-1234'),
			('Hello 123-123-1234 323-223-4234', '123-123-1234')
		]
		for test_case in test_cases:
			self.assertEqual(downloader.get_phone_number_from(test_case[0]), test_case[1])


if __name__ == '__main__':
	unittest.main()
