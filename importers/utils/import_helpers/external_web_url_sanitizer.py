import urllib


def remove_protocol(url):
	if '://' in url:
		return url[url.find('://') + 3:]
	else:
		return url


def use_dot_com(url):
	"""
	Removes the protocol and makes sure the host name uses .com
	"""
	# Just so we don't have to assume the protocol was already removed, remove it.
	url = remove_protocol(url)
	if '/' in url:
		index = url.find('/')
		host_name = url[:index]
		request_path = url[index:]
	else:
		host_name = url
		request_path = ''
	if '.' in host_name:
		index = host_name.rfind('.')
		top_level_domain_name = host_name[index + 1:]
	else:
		# Incredibly rare case when the url is almost invalid.
		top_level_domain_name = host_name
	if top_level_domain_name == 'com':
		return url
	else:
		return host_name[:-len(top_level_domain_name)] + 'com' + request_path


def simplify_url(url):
	url = use_dot_com(url.strip())
	if 'www.' in url and url.find('www.') == 0:
		url = url[4:]

	# Cut off some language identifiers if they're specified.
	# They don't uniquely identify a location.
	cut_trailing = ['/', '/en-ca', '/en-uk', '/en-us']
	for token in cut_trailing:
		if token in url.lower() and url.lower().find(token) == len(url) - len(token):
			url = url[:-len(token)]
	
	return url


def soft_match(url1, url2):
	"""
	Performs a rough equality check on the two urls.
	This is used to look for urls for an individual location that are 
	actually not specific to them but rather general to many locations in a location group.
	
	We don't want to maintain copies of the location group's external web url in each location.
	"""
	url1 = simplify_url(url1)
	url2 = simplify_url(url2)
	return url1 == url2


def sanitize_google_redirect_urls(url):
	# If this is a google redirect,
	# convert to a direct link.
	if url and 'google.' in url and 'url=' in url:
		index = url.find('url=')
		url = url[index + 4:]
		if '&' in url:
			index = url.find('&')
			url = url[:index]
		return urllib.unquote(url).decode('utf-8')
	else:
		return url


def get_sanitized_external_web_url(location, location_groups):
	if not location['external_web_url']:
		return location['external_web_url']
	for group in location_groups:
		if soft_match(group['external_web_url'], location['external_web_url']):
			return None
	return sanitize_google_redirect_urls(location['external_web_url'])