from import_helpers.string_matcher_repo import StringMatcherRepo

location_tags_repo = StringMatcherRepo('data/location_tags/location_tags.json')


def applies_to(location_name, location_tag_id):
	return location_tags_repo.applies_to(location_name, location_tag_id)
