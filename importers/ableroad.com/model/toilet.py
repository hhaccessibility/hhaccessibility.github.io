# corresponds with the tabbed interface you see on pages like: 
# http://ableroad.com/detail.php?index=1&newID=oberon-cambridge&s=&s1=&cat=3&hide=0
accessibility_breakdown = {
	'mobility': ['parking', 'path_of_travel', 'directional_signage', 'path_to_entrances', 'counters_bars_registers',
		'overall_interior_access', 'lobby_reception_area', 'reach', 'customer_service', 'restrooms',
		'multifloor_access', 'evacuation_information'],
	'hearing': ['hearing_signage', 'captioning_on_tv', 'videophone_or_tty', 'asl_interpreter_cart',
		'vibrating_flashing_pagers', 'amplified_phone', 'asl_staff', 'lighting_levels', 'sensitive_staff',
		'assistive_listening_system', 'noise_levels', 'strobe_fire_alarms'],
	'sight': ['public_transportation', 'phone_information', 'announcements', 'website_accessibility',
		'sufficient_lighting', 'audio_video', 'braille_information', 'vision_signage', 'aisles_navigation',
		'large_print', 'staff_assistance', 'guide_dog_service_animal'],
	'cognitive': ['easy_path_of_travel', 'oral_information', 'illustrative_communication', 'spaces_clearly_marked',
		'live_phone_support', 'simple_door_operation', 'easy_to_read_directories', 'credit_card_alternatives',
		'readily_available_assistance', 'respectful_staff', 'knowledgeable_staff', 'evacuation_policies']
}

class Toilet(object):
	def __eq__(self, other):
		return (isinstance(other, self.__class__) 
			and self.name == other.name
			and self.street_address == other.street_address
			and self.neighbourhood == other.neighbourhood
			and self.locality == other.locality
			and self.state == other.state)

	def __ne__(self, other):
		return not self.__eq__(other)

	def __hash__(self):
		return hash(self.name + self.street_address + self.neighbourhood + self.locality + self.state)

	def __init__(self, name, street_address, neighbourhood, locality, state, zipcode,
	phone_number, distance, categories, yelp_rating, yelp_num_ratings, yelp_review_start, ableroad_rating, ableroad_num_ratings, ableroad_review_text,
	thumbnail_url, details_url):
		self.name = name
		self.street_address = street_address
		self.neighbourhood = neighbourhood
		self.locality = locality
		self.zipcode = zipcode
		self.state = state
		self.phone_number = phone_number
		self.categories = categories
		self.distance = distance
		self.yelp_rating = yelp_rating
		self.yelp_num_ratings = yelp_num_ratings
		self.yelp_review_start = yelp_review_start
		self.ableroad_rating = ableroad_rating
		self.ableroad_num_ratings = ableroad_num_ratings
		self.ableroad_review_text = ableroad_review_text
		self.thumbnail_url = thumbnail_url
		self.details_url = details_url
		self.longitude = ''
		self.latitude = ''
