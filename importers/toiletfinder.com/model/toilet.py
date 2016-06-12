class Toilet(object):
	def __init__(self, name, total_logged_dumps, street_address, postal_code, locality, country, phone_number, toilet_paper_type, venue_category = None):
		self.name = name
		self.total_logged_dumps = total_logged_dumps
		self.street_address = street_address
		self.postal_code = postal_code
		self.locality = locality
		self.country = country
		self.toilet_paper_type = toilet_paper_type
		self.venue_category = venue_category

