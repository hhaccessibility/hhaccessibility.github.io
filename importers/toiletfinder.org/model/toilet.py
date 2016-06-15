class Toilet(object):
	def __init__(self, name, location_number, average_rating, num_votes, lat, long, 
restrictions, hours, seasons, cost, added_by, 
for_male, for_female, for_disabled, for_baby_changing, for_radar_key, nearby_toilet_ids, comments):
		self.name = name
		self.id = location_number
		self.average_rating = average_rating
		self.num_votes = num_votes
		self.lat = lat
		self.long = long
		self.restrictions = restrictions
		self.hours = hours
		self.seasons = seasons
		self.cost = cost
		self.added_by = added_by
		self.for_male = for_male
		self.for_female = for_female
		self.for_disabled = for_disabled
		self.for_baby_changing = for_baby_changing
		self.for_radar_key = for_radar_key
		self.nearby_toilet_ids = nearby_toilet_ids
		self.comments = comments

