from import_helpers.string_matcher_repo import StringMatcherRepo

location_tags_repo = StringMatcherRepo('data/location_tags/location_tags.json')


def applies_to(location_name, location_tag_id):
	return location_tags_repo.applies_to(location_name, location_tag_id)


def get_all_appropriate_location_tags_for(location_name, tag_ids):
	return [tag_id for tag_id in tag_ids if location_tags_repo.applies_to(location_name, tag_id)]