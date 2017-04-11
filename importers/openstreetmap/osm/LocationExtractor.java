package osm;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.w3c.dom.Document;
import java.util.List;
import java.util.LinkedList;
import java.io.File;
import java.io.IOException;

public class LocationExtractor
{
  private static Location getNearbyLocation(double longitude, double latitude, List<Location> locations)
  {
	  double coordinateThreshold = 0.000001;
	  for (Location location: locations)
	  {
		  if (Math.abs(longitude - location.getLongitude()) < coordinateThreshold &&
			Math.abs(latitude - location.getLatitude()) < coordinateThreshold) {
				return location;
			}
	  }
	  return null;
  }
  
  private static List<Location> removeDuplicateLocations(List<Location> newLocations, List<Location> otherLocations)
  {
	  LinkedList<Location> result = new LinkedList<Location>();
	  for (Location loc: newLocations)
	  {
		  Location nearbyLocation = getNearbyLocation(loc.getLongitude(), loc.getLatitude(), otherLocations);
		  if ( nearbyLocation == null )
		  {
			  result.add(loc);
		  }
		  else
		  {
			  System.out.println("Matching location found for " + loc.getValueFor("name"));
		  }
	  }
	  
	  return result;
  }
	
	public static List<Location> getAllLocationsFromDirectory() throws org.xml.sax.SAXException, 
	javax.xml.parsers.ParserConfigurationException, org.xml.sax.SAXException, IOException
	{
	  String inputDirectory = "raw_xml";
	  List<Location> allLocations = new LinkedList<Location>();

		// loop through all files in the input directory.
		for (File xmlFile: new File(inputDirectory).listFiles())
		{
			// Skip directories.
			if ( !xmlFile.isFile() )
				continue;

			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(xmlFile);
			doc.getDocumentElement().normalize();
			System.out.println("About to load document " + xmlFile.getPath());
			List<Location> newLocations = new LinkedList<Location>();
			newLocations.addAll(NodeProcessor.getLocationsFromNodes(doc));
			System.out.println("Loaded all locations from node elements.  There were " + newLocations.size());
			newLocations.addAll(WayProcessor.getLocationsFrom(doc));
			System.out.println("Loaded all locations from both node and way elements.  There was a total of " + newLocations.size());
			newLocations = removeDuplicateLocations(newLocations, allLocations);
			allLocations.addAll(newLocations);
			System.out.println("Total locations is now: " + allLocations.size());
		}
		return allLocations;
	}
}