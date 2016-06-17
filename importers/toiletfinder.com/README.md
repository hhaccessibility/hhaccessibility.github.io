The toiletfinder.com importer is basically just some code that generally tries to collect information from the toiletfinder.com website.

No API was found so most of the code just downloads HTML and scrapes data out of the HTML.

** Making a CSV **

To make a CSV file that includes all the information that we extracted from toiletfinder.com, run this command:
python generate_csv.py

** dependencies **

python
pip
pip install lxml
pip install cssselect
