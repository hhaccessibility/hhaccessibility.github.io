"""
utils implements a few functions that may be useful in a variety of places.
"""

def list_to_dict(list1):
	result = {}
	for e in list1:
		result[e['id']] = e
	return result
