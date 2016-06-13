class Toilet(object):
	def __eq__(self, other):
		return (isinstance(other, self.__class__) 
			and self.venue_url == other.venue_url 
			and self.thumbnail_url == other.thumbnail_url)

	def __ne__(self, other):
		return not self.__eq__(other)

	def __hash__(self):
		if self.venue_url:
			return hash(self.venue_url)
		else:
			return hash(self.name + self.street_address 
				+ self.postal_code + self.country)

	def __init__(self, name, total_logged_dumps, street_address, postal_code, locality, country, phone_number, toilet_paper_type, thumbnail_url, venue_url, venue_category = None):
		self.name = name
		self.total_logged_dumps = total_logged_dumps
		self.street_address = street_address
		self.postal_code = postal_code
		self.locality = locality
		self.country = country
		self.toilet_paper_type = toilet_paper_type
		self.thumbnail_url = thumbnail_url
		self.venue_url = venue_url
		self.venue_category = venue_category

