## Overview
The importers directory contains all of the tools used to bring location information into the main application's database.

If you wanted to add new locations for a new city of interest, here is the process you'd work through:
1. Pick an importer that will import the data you care about.  OpenStreetMap is a good general purpose importer so let's assume you pick it.
1. Turn the location of interest into a form that the importer understands.  OpenStreetMap would need the longitude and latitude of that city of interest.
1. Use the importer to download and generate a .csv file.
1. Copy that .csv file to importers/utils and run a command like 'python csv_importer.py "locations.csv"' to adjust the application's seed data files.
1. Go to the hhaccessibility.github.io\app directory and recreate the database. 
Recreating the database would involve running these commands:
php artisan migrate:rollback
php artisan migrate
php artisan db:seed

## Importers
Each importer is a tool used for collecting data from its associated data source and putting location data in CSV format.

For example, the OpenStreetMap importer has a feature to download XML data for a small area around a given longitude and latitude.  It has a separate feature for converting the XML to CSV.  Overall, it helps anyone wanting to collect data on a new location get data from OpenStreetMap into a CSV file.

## CSV file to Seed Data
There are some Python scripts in importers/utils for converting a CSV file to appropriate changes on the seed data files of the main application.
