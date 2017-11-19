from sync import sync

db = sync.get_db_connection()
sync.set_fields_on_locations(db)
sync.add_missing_data(db, ['data_source', 'location_group'])