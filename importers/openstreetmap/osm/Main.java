package osm;

import java.io.IOException;
import java.util.List;

public class Main
{  
  public static void main(String argv[]) throws org.xml.sax.SAXException, 
	javax.xml.parsers.ParserConfigurationException, org.xml.sax.SAXException,
	IOException
  {
	  List<Location> allLocations = LocationExtractor.getAllLocationsFromDirectory();
		CsvFileGenerator.generateCSV(allLocations, "locations.csv");
  }

}