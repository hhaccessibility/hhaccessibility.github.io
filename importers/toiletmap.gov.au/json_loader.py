"""
json_loader serves a similar role to html_scraper in toiletfinder.com and toiletfinder.org.
json_loader is responsible for logic involved with getting out of JSON files and into a list of Toilet instances.
"""
import json
import os
from model.toilet import Toilet
from downloader import output_dir

def load_toilets(json_filename):
	"""
	Returns a list of Toilet instances pulled out of the specified JSON file
	"""
	result = []

	# ignore file if it is empty.
	if os.stat(json_filename).st_size == 0:
		return result

	# load the file contents.
	with open(json_filename, 'r') as json_file:
		tree = json.load(json_file)
		for toilet_data in tree['R']:
			id = toilet_data['ID']
			name = toilet_data['T']
			f = toilet_data['F']
			p = toilet_data['P']
			a1 = toilet_data['A1']
			a2 = toilet_data['A2']
			tt = toilet_data['TT']
			date = toilet_data['D']
			sm = toilet_data['SM']
			latitude = toilet_data['LA']
			longitude = toilet_data['LO']
			s = toilet_data['S']
			hours = toilet_data['O']
			locality = toilet_data['L']
			st = toilet_data['ST']
			
        		new_toilet = Toilet(name, id, latitude, longitude, locality, st, f,
        			p, a1, a2, sm, tt, hours, date)
			result.append(new_toilet)

	return result

def load_all_json(printing_progress):
	result = set([])
	count = 0

	# loop through all json files in raw_data directory
	for filename in os.listdir(output_dir):
		if filename.endswith(".json"):
			if printing_progress:
				print 'Processing file ' + str(count) + ': ' + filename
			count = count + 1
			result |= set(load_toilets(output_dir + '/' + filename))

	return result

if __name__ == '__main__':
        load_all_json(True)


