
class LocationDuplicateContainer:
	def __init__(self, location_duplicates):
		self.names = {}
		for location_duplicate in location_duplicates:
			name = location_duplicate['name'].strip().lower()
			if name not in self.names:
				self.names[name] = []
			self.names[name].append(location_duplicate)

	def get_location_duplicates_by_name(self, name):
		name = name.strip().lower()
		if name in self.names:
			return self.names[name]
		return []