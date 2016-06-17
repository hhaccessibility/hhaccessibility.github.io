The toiletfinder.org importer is basically just some code that generally tries to collect information from the toiletfinder.org website.

No API was found so most of the code just downloads HTML and scrapes data out of the HTML.

** making a csv **

To make a CSV, run the command:
python generate_csv.py

** dependencies **

python
pip
pip install lxml
pip install cssselect
