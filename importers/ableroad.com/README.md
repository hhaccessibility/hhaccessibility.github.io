The ableroad.com importer downloads information off ableroad.com and converts to a CSV file.

No API was found so most of the code downloads HTML and scrapes data out of the HTML.

## Making a CSV

To make a CSV file that includes all the information that we extracted from ableroad.com, run this command:
python generate_csv.py

## Collecting information new more locations

For now, the downloader searches '' and 'Windsor, Ontario' but you may want to add more locations.

New locations can be added to the download_html.py script in the download_all function.

## dependencies

python
pip
pip install lxml
pip install cssselect
