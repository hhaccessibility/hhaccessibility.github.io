from sync import sync

db = sync.get_db_connection()
sync.add_missing_data(db, ['data_source', 'location_group', 'location_event'])
sync.set_fields_on_locations(db)
sync.add_missing_data(db, [{
		'name': 'location_location_tag',
		'composite_keys': ['location_id', 'location_tag_id']
	}])
sync.clear_ratings_cache(db)
sync.set_fields_on_location_tags(db)
sync.update_coordinates_for_locations(db)
sync.offset_question_order(db)
sync.add_missing_data(db, ['question'])
sync.set_fields_on_questions(db)
sync.safely_remove_removed_locations(db)
sync.add_locations_not_conflicting_with_user_added_locations(db)
sync.add_missing_users(db)
sync.add_missing_data(db, ['user_answer'])
sync.replace_all_data(db, ['faq_item'])