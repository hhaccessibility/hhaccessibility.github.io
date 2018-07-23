The ableroad.com importer downloads information off ableroad.com and converts to a CSV file.
No API was found so most of the code downloads HTML and scrapes data out of the HTML.

## Dependencies

python
pip
pip install lxml
pip install cssselect

## Making a CSV

To make a CSV file that includes all the information that we extracted from ableroad.com, run this command:
python easy_importer.py


## Explanation

it will retrieve category 2 - 22 and 10 pages for each category
each page will take 5 second
it will generate the following file
# export.csv - the csv format file it generates
# importer.log - log info
# tmp.html - the last file that cached in local directory
