package osm;

import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.io.File;
import java.io.FileWriter;
import java.io.FileOutputStream;
import java.io.IOException;
import javax.xml.parsers.ParserConfigurationException;
import org.xml.sax.SAXException;
import java.util.*;

public class OSM {

	public static String getValueForKey(NodeList tags,String key) {
        for(int loop=0;loop<tags.getLength();loop++) {
			Node node = tags.item(loop);
			Element tag = (Element) node;
			if ( tag.getAttribute("k").equals(key) )
				return tag.getAttribute("v");
		}
		return "";
    }

	private static boolean isRestaurant(NodeList tags)
	{
		String cuisine = getValueForKey(tags, "cuisine");
		String amenity = getValueForKey(tags, "amenity");
		return !cuisine.equals("") || amenity.equals("fast_food") ||
			amenity.equals("restaurant") || amenity.equals("cafe") || amenity.equals("bar");
	}

	private static boolean isEntertainment(NodeList tags)
	{
	   String leisure = getValueForKey(tags, "leisure");
	   String amenity = getValueForKey(tags, "amenity");
	   String office = getValueForKey(tags, "office");
	   String[] matchingAmenities = new String[]{"theatre", "fountain", "pub", "bar"};
		return Arrays.asList(matchingAmenities).contains(amenity) || office.equals("newspaper");
	}

	private static boolean isPark(NodeList tags)
	{
	   String leisure = getValueForKey(tags, "leisure");
	   String amenity = getValueForKey(tags, "amenity");
	
		return amenity.equals("fountain") ||
		leisure.equals("park") || leisure.equals("garden") || leisure.equals("water_park");
	}

	private static boolean isAssociation(NodeList tags)
	{
	   String religion = getValueForKey(tags, "religion");
	   String historic = getValueForKey(tags, "historic");
	   String amenity = getValueForKey(tags, "amenity");
	   String name = getValueForKey(tags, "name").toLowerCase();
	   String denomination = getValueForKey(tags, "denomination").toLowerCase();
		return !religion.equals("") || amenity.equals("place_of_worship")
		|| historic.equals("memorial") || historic.equals("monument") || name.contains("holy")
		|| !denomination.equals("");
	}

	private static boolean isEducation(NodeList tags)
	{
		String amenity = getValueForKey(tags, "amenity");
	   String tourism = getValueForKey(tags, "tourism");
	   String office = getValueForKey(tags, "office");
	   String building = getValueForKey(tags, "building");
	   String name = getValueForKey(tags, "name").toLowerCase();
	   return tourism.equals("artwork") || office.equals("newspaper") || name.equals("hackforge")
	   || name.contains("education")|| name.contains("École")|| name.contains("école") || name.contains("ecole") || name.contains("school") || name.contains("student")
	   || name.contains("university") || name.contains("college") ||
	   building.equals("university") || building.equals("school") || building.equals("college")
	   || amenity.equals("library") || name.indexOf("library") >= 0;
	}

	private static boolean isSports(NodeList tags)
	{
	   String leisure = getValueForKey(tags,"leisure");
	   String sport = getValueForKey(tags,"sport");
	   String source = getValueForKey(tags,"source");
		return !leisure.equals("") || leisure.equals("sports_centre")|| leisure.equals("swimming_pool") || leisure.equals("playground")||
				leisure.equals("pitch") || sport.equals("baseball") ||source.equals("Bing");
	}

	private static boolean isPublicService(NodeList tags)
	{
	   String pservice = getValueForKey(tags, "amenity");
	   String ppublic = getValueForKey(tags, "public");
	   String tourism = getValueForKey(tags, "tourism");
	   String service = getValueForKey(tags, "service");
	   
		return pservice.equals("police") || pservice.equals("social_facility")
				|| tourism.equals("information")
				|| !ppublic.equals("") || pservice.equals("fire_station");
	}

	private static boolean isShopping(NodeList tags)
	{
	   String shop = getValueForKey(tags, "shop");
	   String amenity = getValueForKey(tags, "amenity");
		return !shop.equals("") || amenity.equals("bicycle_repair_station");
	}

	private static boolean isFinancial(NodeList tags)
	{
	   String amenity = getValueForKey(tags, "amenity");
	   String name = getValueForKey(tags, "name").toLowerCase();
		return name.startsWith("bank") || name.endsWith("bank") || amenity.equals("bank") || amenity.equals("atm");
	}

	private static boolean isTransportation(NodeList tags)
	{
	   String highway = getValueForKey(tags, "highway").toLowerCase();
	   String barrier = getValueForKey(tags, "barrier").toLowerCase();
	   String railway = getValueForKey(tags, "railway").toLowerCase();
	   String bus = getValueForKey(tags, "bus").toLowerCase();
	   String name = getValueForKey(tags, "name").toLowerCase();
	   String public_transport = getValueForKey(tags, "public_transport");
	   
		return name.indexOf("airport") >= 0 || name.indexOf("train station") >= 0 ||
			name.indexOf("charter") >= 0 || 
			name.indexOf("transit station") >= 0 || name.indexOf("bus station") >= 0 || bus.equals("yes")
			|| highway.equals("bus_stop")
			|| !railway.equals("") || !public_transport.equals("");
	}

	private static boolean isAccomodation(NodeList tags)
	{
	   String barrier = getValueForKey(tags, "barrier");
	   String tourism= getValueForKey(tags, "tourism");
		return tourism.equals("hotel") || tourism.equals("motel") || tourism.equals("hostel")
		|| tourism.equals("bnb");
	}

	private static boolean isHealthcare(NodeList tags)
	{
	   String name = getValueForKey(tags, "name").toLowerCase();
	   String amenity = getValueForKey(tags, "amenity").toLowerCase();
	   String emergency = getValueForKey(tags, "emergency").toLowerCase();
		return name.contains("hospital") || name.contains("clinic") || name.contains("medical") || name.contains("Pharma")
		|| name.contains("optom") || name.contains("dentist") || amenity.equals("hospital") || amenity.equals("clinic")
		|| emergency.equals("yes") || name.contains("drugmart") || name.equals("rexall");
	}
	
	private static boolean isWheelchairAccessible(NodeList tags)
	{
		String wheelchair = getValueForKey(tags, "wheelchair");
		return wheelchair.equals("yes");
	}
	
	private static boolean isToiletWheelchairAccessible(NodeList tags)
	{
		String toiletWheelchair = getValueForKey(tags, "toilets:wheelchair");
		return toiletWheelchair.equals("yes");
	}
	
	private static boolean hasFreeWifi(NodeList tags)
	{
		String wifi = getValueForKey(tags, "wifi");
		if ( wifi != null && wifi.equals("free") )
			return true;
		
		wifi = getValueForKey(tags, "internet_access:fee");
		if ( wifi != null && wifi.equals("no") )
			return true;
		
		return false;
	}
	
	private static boolean hasWifi(NodeList tags)
	{
		String [] wifiProperties = new String[] { "wifi",
			"internet_access:fee", "internet_access"};
		for (String key: wifiProperties)
		{
			if ( !getValueForKey(tags, key).equals("") )
				return true;
		}
		return false;
	}
	
	private static boolean isLocationOfInterest(NodeList tags)
	{
		String [] mustBeEmpty = new String[]{"surveillance", "bicycle_parking", "railway", "highway"};
		for (String key: mustBeEmpty)
		{
			if ( !getValueForKey(tags, key).equals("") )
				return false;
		}
		if ( getValueForKey(tags, "barrier").equals("bollard") ) {
			// filter out notices of road closures.
			return false;
		}
		String amenity = getValueForKey(tags, "amenity");
		
		// don't include parking lots
		if ( amenity.equals("parking") )
			return false;
		
		String name = getValueForKey(tags, "name");
		if( name.equals("Windsor") )
			return false;
		
		return true;
	}

	/**
	Retrieves all locations from the document out of node elements.

	This does not extract any information from way elements.  
	way elements is the responsibility of WayProcessor.
	*/
  private static List<Location> getLocationsFromNodes(Document doc)
  {
	NodeList nList = doc.getElementsByTagName("node");
	LinkedList<Location> result = new LinkedList<Location>();
	for (int temp = 0; temp < nList.getLength(); temp++) {
		Node nNode = nList.item(temp);
		if (nNode.getNodeType() == Node.ELEMENT_NODE) {
			Element eElement = (Element) nNode;
			NodeList tags = eElement.getElementsByTagName("tag");
			String name = getValueForKey(tags, "name");

			if ( !name.equals("") )
			{
				Location newLocation = new Location(Double.parseDouble(eElement.getAttribute("lon")), Double.parseDouble(eElement.getAttribute("lat")), tags);
				result.add(newLocation);
			}
		}
	}
	return result;
  }

  private static void generateCSV(List<Location> locations, String filename) throws IOException
  {
	FileWriter writer = new FileWriter(filename);
	csv.writeValues(writer, "Name", "Longitude", "Latitude", "Phone", "Address", "URL", "Is Restaurant",
	"Is Entertainment", "Is Sports", "Is Education",
	"Is Association","Public Service","Is Transportation",
	"Is Accomodation","Is Shopping","Is Park", "Is Financial", 
	"Is Healthcare", "Is wheelchair accessible", "Has Wheelchair Accessible Toilets", "Wifi", "Free Wifi");

	for ( Location location: locations )
	{
		// skip locations that we don't want to rate.
		if ( !isLocationOfInterest(location.getTags()) ) {
			continue;
		}
		NodeList tags = location.getTags();
		String phone = getValueForKey(tags, "phone");
		String house_number = getValueForKey(tags, "addr:housenumber");
		String postalCode = getValueForKey(tags, "addr:postcode");
		String street = getValueForKey(tags, "addr:street");
		String city = getValueForKey(tags, "addr:city");
		String province = getValueForKey(tags, "addr:province");
		String country = getValueForKey(tags, "addr:country");
		String url = getValueForKey(tags, "website");

		// a little sanitization
		if ( !url.equals("") && !url.toLowerCase().startsWith("http:") 
			&& !url.toLowerCase().startsWith("https:") ) {
			url = "http://" + url;
		}

		// For consistency's sake, use Windsor instead of City of Windsor.
		if ( city.equals("City of Windsor") )
			city = "Windsor";
		if ( province.equals("") )
			province = getValueForKey(tags, "addr:state");

		String name = getValueForKey(tags, "name");
		String address = (postalCode + " " + house_number + " " + street + " " + city).trim();
		if ( !province.equals("") ) {
			if ( !address.equals("") )
				address = address + ", ";

			address += province;
		}
		csv.writeValues(writer, name, "" + location.getLongitude(), "" + location.getLatitude(),
		phone, address, url, "" + isRestaurant(tags), "" + isEntertainment(tags), "" + isSports(tags),
		"" + isEducation(tags), "" + isAssociation(tags),
		"" + isPublicService(tags), "" + isTransportation(tags), "" + isAccomodation(tags),
		"" + isShopping(tags), "" + isPark(tags), "" + isFinancial(tags), "" + isHealthcare(tags), 
		"" + isWheelchairAccessible(tags), "" + isToiletWheelchairAccessible(tags),
		"" + hasWifi(tags), "" + hasFreeWifi(tags)
		);
	}

	writer.flush();
	writer.close();
	System.out.println("All locations written to csv file");
  }
  
  private static Location getNearbyLocation(double longitude, double latitude, List<Location> locations)
  {
	  double coordinateThreshold = 0.000001;
	  for (Location location: locations)
	  {
		  if (Math.abs(longitude - location.getLongitude()) < coordinateThreshold &&
			Math.abs(latitude - location.getLatitude())) {
				return location;
			}
	  }
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
			  System.out.println("Matching location found between " + loc.getName());
		  }
	  }
	  
	  return result;
  }
  
  public static void main(String argv[]) throws Exception
  {
	  String inputDirectory = "raw_xml";
	  List<Location> allLocations = new LinkedList<Location>();

		// loop through all files in the input directory.
		for (File xmlFile: new File(inputDirectory).listFiles())
		{
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(xmlFile);
			doc.getDocumentElement().normalize();
			System.out.println("About to load document " + xmlFile.getPath());
			List<Location> newLocations = new LinkedList<Location>();
			newLocations.addAll(getLocationsFromNodes(doc));
			System.out.println("Loaded all locations from node elements.  There were " + newLocations.size());
			newLocations.addAll(WayProcessor.getLocationsFrom(doc));
			System.out.println("Loaded all locations from both node and way elements.  There was a total of " + newLocations.size());
			newLocations = removeDuplicateLocations(newLocations, allLocations);
			allLocations.addAll(newLocations);
			System.out.println("Total locations is now: " + allLocations.size());
		}
		generateCSV(allLocations, "locations.csv");
  }

}