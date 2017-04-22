## Downloading Data for a new area
1. Find the longitude and latitude for the area you're interested in.  This could be done using Google Maps and zooming in as much as possible before taking the coordinates out of the URL.
1. Open a console/terminal to run a command like the following with your coordinates substituted in.
java osm.Downloader longitude latitude
1. You should now have a new .xml file in the raw_xml directory.  It would include data for a rectangular area around the coordinates you specified.  At the time of writing this, the range was 0.03 degrees + - for latitude and longitude which was hardcoded in Downloader.java.

## Converting downloaded XML data to CSV
1. If you haven't downloaded the data you want in CSV, read instructions for that above.
1. Assuming you have the XML data on your machine in the raw_xml directory, run a command like:
java osm.Main
1. Wait for it to complete.  It may take a few minutes if it is processing several MB of XML data.
1. You should have a locations.csv.

## Reviewing CSV
The OpenStreetMap importer doesn't work perfectly and the OpenStreetMap data set isn't perfect so it is worth the time to review CSV data before bringing it into AccessLocator.
Problems to look for include:
- Duplicate locations.  A duplicate location would be a pair of rows/records corresponding with the same ratable location.
For example, "Bruce Park" and "Bruce Ave Park" were found in OpenStreetMap data and both located within a couple hundred meters of each other.  It would be the same park in real life but OpenStreetMap data had it under 2 different names.
They were also more than 5 meters apart so it was difficult to detect the problem in Java and ignore one of them.
- Unratable locations.  It isn't useful to rate accessibility of a river so we don't want them in our AccessLocator data.  These locations are mostly filtered out by code in OfInterestDecider.java but it doesn't always work perfectly.
- Incorrect location tags.  Gas stations should be TRUE for the transportation column.  Apartments, retirement homes, hotels... should be TRUE in the accommodation column.  All medical clinics, dental offices, eye doctors should be TRUE under healthcare.  The code that automatically decides these are in osm\location_fields and it isn't perfect.  Finding mistakes and tweaking the corresponding code in Java fixes your current data and reduces the recurrance of the problem for later.
