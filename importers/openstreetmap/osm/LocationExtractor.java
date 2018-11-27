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
	/**
	* Finds a location that is in virtually the exact same coordinates as specified.
	* The tolerance is so small that it is just allowing for floating point error.  
	* The tolerance isn't enough to account for a neighbouring location just a few meters away.
	*/
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
  
  /**
  * Tries to find a location in locations that matches the specified id.
  * Assumes that id is not null.
  */
  private static Location getLocationById(String id, List<Location> locations) {
	  for (Location location: locations)
	  {
		  if (id.equals(location.getId())) {
			  return location;
		  }
	  }
	  return null;
  }
  
  private static String simplifyName(String name) {
	  return name.trim().replaceAll("\\s+", " ").toLowerCase();
  }
  
  private static String removeSpaces(String name) {
	  return name.replaceAll("\\s", "");
  }
  
	/**
	Returns distance in meters across the Earth's curvature between the specified coordinates.
	*/
  private static double getDistance(Location location1, Location location2) {
		double lon1 = location1.getLongitude() * 180 / Math.PI;
		double lat1 = location1.getLatitude() * 180 / Math.PI;
		double lon2 = location2.getLongitude() * 180 / Math.PI;
		double lat2 = location2.getLatitude() * 180 / Math.PI;
		double earthRadius = 6371000; // meters
		double deltaLong = lon2 - lon1;
		double deltaLat = lat2 - lat1;
		double a = ( Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
		Math.cos(lat1) * Math.cos(lat2) *
		Math.sin(deltaLong / 2) * Math.sin(deltaLong / 2) );
		double c = 2 * Math.atan2( Math.sqrt( a ), Math.sqrt( 1 - a ) );
		return earthRadius * c;
  }

  private static Location getLocationWithVerySimilarNameAndLocation
  (Location location, List<Location> otherLocations) {
	  double smallThresholdMeters = 250;
	  double largerThresholdMeters = 300;
	  for (Location otherLocation: otherLocations)
	  {
		  double distance = getDistance(location, otherLocation);
		  String name1 = simplifyName(location.getValueFor("name"));
		  String name2 = simplifyName(otherLocation.getValueFor("name"));
		  if (name1.equals(name2) && distance < largerThresholdMeters) {
			  return location;
		  }
		  // a slightly more wide match for names with a slightly more specificaly matched coordinates
		  if (removeSpaces(name1).equals(removeSpaces(name2)) && distance < smallThresholdMeters) {
			  return location;
		  }
	  }
	  return null;
  }
  
  private static Location findLikelyDuplicate(Location loc, List<Location> locations) {
	  Location nearbyLocation = getNearbyLocation(loc.getLongitude(), loc.getLatitude(), locations);
	  if ( nearbyLocation == null && loc.getId() != null && !loc.getId().isEmpty() )
	  {
		  nearbyLocation = getLocationById(loc.getId(), locations);
	  }
	  if ( nearbyLocation == null )
	  {
		  nearbyLocation = getLocationWithVerySimilarNameAndLocation(loc, locations);
	  }
	  return nearbyLocation;
  }

  /**
  * Returns a List of the newLocations which are not duplicated in otherLocations.
  */
  private static List<Location> removeDuplicateLocations(List<Location> newLocations, List<Location> otherLocations)
  {
	  LinkedList<Location> result = new LinkedList<Location>();
	  for (Location loc: newLocations)
	  {
		  Location likelyDuplicate = findLikelyDuplicate(loc, result);
		  if (likelyDuplicate == null)
			  likelyDuplicate = findLikelyDuplicate(loc, otherLocations);
		  if ( likelyDuplicate == null )
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
			if ( !xmlFile.isFile() || !xmlFile.getName().endsWith(".xml")  )
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