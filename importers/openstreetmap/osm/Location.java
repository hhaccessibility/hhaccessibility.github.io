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
	private HashMap<String, String> keyValues = new HashMap<String, String>();
	
	public Location(double longitude, double latitude, NodeList tags)
	{
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