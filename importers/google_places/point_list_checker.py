import generate_csv
import math

point_radius = 30 # meters


def get_direct_distance(lat1, lon1, lat2, lon2):
	"""
	Returns distance in km across the Earth's curvature between the specified coordinates.
	
	lat1, lon1, lat2, lon2 should be in degrees.
	
	This is basically a translation of a very similar method in BaseUser class implemented in PHP.
	"""
	earthRadius = 6371000 # meters
	lon1 = math.radians(lon1)
	lat1 = math.radians(lat1)
	lon2 = math.radians(lon2)
	lat2 = math.radians(lat2)
	deltaLong = lon2 - lon1
	deltaLat = lat2 - lat1
	a = ( math.sin(deltaLat / 2) * math.sin(deltaLat / 2) +
		math.cos(lat1) * math.cos(lat2) *
		math.sin(deltaLong / 2) * math.sin(deltaLong / 2) )
	c = 2 * math.atan2( math.sqrt( a ), math.sqrt( 1 - a ) )
	return earthRadius * c

def exactly_equal(p1, p2):
	return ( p1['latitude'] == p2['latitude'] 
		and p1['longitude'] == p2['longitude'] )


def get_places_from_points():
	with open('coordinates_to_check.csv', 'r') as f:
		content = f.read()
		lines = content.split("\n")
		lines = [line.strip() for line in lines if line.strip() != '' and ',' in line]
		points = [[part.strip() for part in line.split(",")] for line in lines]
		points = [{'latitude': float(parts[0]), 'longitude': float(parts[1])} for parts in points]
		points = sorted(points, key=lambda point: point['longitude'])
		points_to_remove = []
		for point in points:
			if point in points_to_remove:
				continue
			for other_point in points:
				if ( other_point not in points_to_remove and not exactly_equal(point, other_point) and
				get_direct_distance(point['latitude'], point['longitude'], other_point['latitude'], other_point['longitude']) < point_radius):
					points_to_remove.append(other_point)
		
		# remove points that are so close that they'd be in the same search radius.
		points = [p for p in points if p not in points_to_remove]
		results = []
		i = 0
		for point in points:
			i += 1
			results.extend(generate_csv.get_places_for_circular_search(point['latitude'], point['longitude'], point_radius))
			if i % 10 == 0:
				print 'Getting places for point ' + str(i) + ' of ' + str(len(points))

		return generate_csv.remove_place_ids_to_skip(generate_csv.remove_duplicate_places(results))


if __name__ == '__main__':
	places = get_places_from_points()
	print 'Got all places: ' + str(len(places))
	print 'About to get details and write to CSV'
	generate_csv.write_csv_file('windsor_places.csv', places)
	generate_csv.update_place_ids_file(places)
