from import_helpers.string_matcher_repo import StringMatcherRepo

location_groups_repo = StringMatcherRepo('data/location_groups/location_groups.json')


def get_location_group_for(location_name):
	location_group_ids = location_groups_repo.get_item_ids()
	for group_id in location_group_ids:
		if location_groups_repo.applies_to(location_name, group_id):
			return group_id

	return None