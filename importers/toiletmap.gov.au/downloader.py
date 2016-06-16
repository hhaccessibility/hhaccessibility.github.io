import urllib2
import os.path
import math

# degrees range for the geographic rectangle for the api
lat_range = 4
longitude_range = 8

output_dir = 'raw_data'

def download_data(latitude, longitude, skip_if_downloaded):
	lat2 = latitude + lat_range
	long2 = longitude + longitude_range
	filename = output_dir + '/data_' + str(latitude) + '_' + str(longitude) + '.json'
	if os.path.isfile(filename):
		return
	url = 'https://toiletmap.gov.au/api/MapAPI/GetMapDetails?areaId=&swLat=' + str(latitude) + '&swLon=' + str(longitude) + '&neLat=' + str(lat2) + '&neLon=' + str(long2) + '&zoom=7&address=Sydney%2C+New+South+Wales%2C+Australia&searchmask=63&IsOpenNow=&clientTime=Thu%2C+16+Jun+2016+01%3A12%3A39+GMT'
	response = urllib2.urlopen(url)
	data = response.read()
	with open(filename, 'wb') as f:
		f.write(data)

def download_all(skip_if_downloaded, printing_progress):
	min_latitude = -45
	max_latitude = -10
	min_longitude = 110
	max_longitude = 155
	total_num_files = (math.ceil((max_latitude - min_latitude) / lat_range) * 
		math.ceil((max_longitude - min_longitude) / longitude_range)
		)
	# ensure the output directory exists.
	if not os.path.isdir(output_dir):
		os.mkdir(output_dir)

	files_processed = 0
	for latitude in range(min_latitude, max_latitude, lat_range):
		if printing_progress:
			print 'processing latitude ' + str(latitude)
			print 'processing file ' + str(files_processed) + ' of ' + str(total_num_files)

		for longitude in range(min_longitude, max_longitude, longitude_range):
			download_data(latitude, longitude, skip_if_downloaded)
			files_processed += 1

if __name__ == '__main__':
	download_all(True, True)


