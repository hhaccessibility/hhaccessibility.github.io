import math


class LocationContainer:
	"""
	AccessLocator contains over 20,000 at the time of writing this comment which is enough to 
	slow our O(n^2) duplicate finding algorithms to a few minutes when adding a few thousand new locations.

	This LocationContainer class is intended to optimize the finding of duplicates.
	One technique is by moving all locations of a similar latitude into the same "bucket".
	Eventually, each bucket may be refactored from a list to a balanced binary search tree which is sorted by longitude.
	"""
	def __init__(self, locations):
		# record the order of location ids so it is preserved by get_all_locations_for_seed_data.
		self.location_ids = [loc['id'] for loc in locations]
		self.location_id_lookups = {}
		for loc in locations:
			self.location_id_lookups[loc['id']] = loc
	
		# initialize buckets.
		num_buckets = max(1, len(locations) / 25)
		self.buckets = []
		self.location_keys = []
		if len(locations) > 0:
			self.location_keys = locations[0].keys()

		for i in range(num_buckets):
			max_lat = (i + 1) * 180.0 / num_buckets - 90
			if i == num_buckets - 1:
				max_lat = 999
			min_lat = i * 180.0 / num_buckets - 90
			if i == 0:
				min_lat = -999
			self.buckets.append([loc for loc in locations if loc['latitude'] < max_lat and loc['latitude'] >= min_lat])

	def is_empty(self):
		for bucket in self.buckets:
			if len(bucket) != 0:
				return False
		return True

	def get_location_keys(self):
		return self.location_keys

	def get_location_by_id(self, location_id):
		if location_id in self.location_id_lookups:
			return self.location_id_lookups[location_id]

	def get_all_locations_for_seed_data(self):
		"""
		Returns the ids in the same order as the list used to create this container.

		Any locations inserted after initialization may be in a fairly random order.
		"""
		for id in self.location_ids:
			loc = self.get_location_by_id(id)
			if loc:
				yield loc

		ids = set(self.location_ids)
		for bucket in self.buckets:
			for loc in bucket:
				if loc['id'] not in ids:
					yield loc

	def insert(self, location):
		"""
		Inserts a location into this container
		
		@param location is a dict
		"""
		index = self.get_bucket_index_from_latitude(location['latitude'])
		self.buckets[index].append(location)
		self.location_id_lookups[location['id']] = location

	def get_bucket_index_from_latitude(self, latitude):
		if latitude < -90:
			return 0
		if latitude > 90:
			return len(self.buckets) - 1
		return int(math.floor((latitude + 90) * len(self.buckets) / 180))

	def get_bucket_index_range_for(self, longitude, latitude, max_distance):
		"""
		@param longitude is in degrees from -180 to 180
		@param latitude is in degrees from -90 to 90
		@param max_distance is in km
		"""
		pi = 3.14159265358979
		earth_radius = 6371 # km
		# Get degrees latitude corresponding with half of max_distance.
		degree_distance = max_distance * 360 / earth_radius / pi
		
		# Get the latitude that would be directly north of the specified 
		# latitude and longitude at exactly max_distance kilometers.
		min_lat = latitude - degree_distance
		
		# Get the latitude that would be directly south at max_distance km. 
		max_lat = latitude + degree_distance

		return {
			'min_index': self.get_bucket_index_from_latitude(min_lat),
			'max_index': self.get_bucket_index_from_latitude(max_lat)
		}

	def locations_near(self, longitude, latitude, max_distance):
		"""
		A generator for locations within the specified distance
		"""
		index_range = self.get_bucket_index_range_for(longitude, latitude, max_distance)
		for i in range(index_range['min_index'], index_range['max_index'] + 1):
			for location in self.buckets[i]:
				yield location
