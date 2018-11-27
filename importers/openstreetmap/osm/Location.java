package osm;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.util.HashMap;

public class Location
{
	private double longitude;
	private double latitude;
	private String id;
	private HashMap<String, String> keyValues = new HashMap<String, String>();

	/**
	Initializes a new Location

	@param longitude is the new location's longitude in degrees from -180 to 180.
	@param latitude is the new location's latitude in degrees from -90 to 90.
	@param tags are the tag elements that have information about this new location.
	@param id is the id of the corresponding node element from OpenStreetMap XML.
	If not null, id should be unique for all locations.  
	In other words, if any 2 instances of Location share the same non-null id, they must be duplicates.
	*/
	public Location(double longitude, double latitude, NodeList tags, String id)
	{
		this.id = id;
		this.longitude = longitude;
		this.latitude = latitude;
		for ( int i = 0; i < tags.getLength(); i++ )
		{
			Element tag = (Element) (tags.item(i));
			String key = tag.getAttribute("k");
			String value = tag.getAttribute("v");
			if ( key != null && value != null )
				keyValues.put(key, value);
		}
	}

	public String getId()
	{
		return id;
	}
	
	public double getLongitude()
	{
		return longitude;
	}
	
	public double getLatitude()
	{
		return latitude;
	}
	
	public String getValueFor(String key)
	{
		String result = keyValues.get(key);
		if ( result == null )
			return "";
		else
			return result;
	}
}