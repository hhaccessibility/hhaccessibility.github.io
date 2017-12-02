from sync import sync

db = sync.get_db_connection()
sync.set_fields_on_locations(db)
sync.set_fields_on_location_tags(db)
sync.add_missing_data(db, ['data_source', 'location_group'])
sync.safely_remove_removed_locations(db)