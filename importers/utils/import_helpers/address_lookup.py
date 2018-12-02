import urllib
import json
import env_loader
import re
import os.path


env_data = env_loader.get_env_data()
google_api_env_key = 'GOOGLE_MAP_API_KEY_FOR_SERVER'
if google_api_env_key not in env_data:
    print('You must specify ' + google_api_env_key + ' in the app/.env file to use the get_coordinates function.')
    api_key = None
else:
    api_key = env_data[google_api_env_key]
coordinates_cache_file_name = 'data/address_lookup_cache.csv'
coordinates_cache = None


def sanitize_address_query(address_query):
    """
    Removes details from the address query that shouldn't change the resulting coordinates 
    but might cause a meaningfully equal address from not being matched in the cache.
    """
    if not isinstance(address_query, basestring):
        raise ValueError('Address must be a string.')

    replace_with_spaces = ',.?:;()[]'
    for c in replace_with_spaces:
        address_query = address_query.replace(c, ' ')

    address_query = re.sub('\s+', '_', address_query).strip().lower()
    return address_query


def get_cached_coordinates():
    global coordinates_cache
    if coordinates_cache is None:
        coordinates_cache = {}
        if os.path.isfile(coordinates_cache_file_name):
            with open(coordinates_cache_file_name, 'r') as f:
                content = f.readlines()
                for line in content:
                    parts = line.strip().split(',')
                    if len(parts) == 3:
                        address = parts[0].strip()
                        longitude = parts[1].strip()
                        latitude = parts[2].strip()
                        coordinates_cache[address] = (float(longitude), float(latitude))
                    else:
                        print('line has too many commas: ' + line)

    return coordinates_cache


def set_coordinates_in_cache(address_query, coordinates_tuple):
    global coordinates_cache
    address_query = sanitize_address_query(address_query)
    coordinates_cache[address_query] = coordinates_tuple
    with open(coordinates_cache_file_name, 'a') as f:
        f.write("\n%s,%f,%f" % (address_query, coordinates_tuple[0], coordinates_tuple[1]))


def get_coordinates_from_cache(address_query):
    address_query = sanitize_address_query(address_query)
    coords = get_cached_coordinates()
    if address_query in coords:
        return coords[address_query]
    else:
        return (None, None) # indicate not found in cache.


def get_coordinates(address_query):
    cached_result = get_coordinates_from_cache(address_query)
    if cached_result[0] is not None and cached_result[1] is not None:
        return cached_result

    if api_key is None:
        return None, None

    query = address_query.encode('utf-8')
    params = {
        'address': query,
		'key': api_key,
        'sensor': "false"
    }
    googleGeocodeUrl = 'https://maps.googleapis.com/maps/api/geocode/json?'
    url = googleGeocodeUrl + urllib.urlencode(params)
    json_response = urllib.urlopen(url)
    response = json.loads(json_response.read())
    if response['results']:
        location = response['results'][0]['geometry']['location']
        latitude, longitude = location['lat'], location['lng']
        set_coordinates_in_cache(address_query, (longitude, latitude))
    else:
        print(json.dumps(response))
        latitude, longitude = None, None
    return latitude, longitude