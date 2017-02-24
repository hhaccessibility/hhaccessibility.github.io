package osm;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;

public class Location
{
	private double longitude;
	private double latitude;
	private NodeList tags;
	
	public Location(double longitude, double latitude, NodeList tags)
	{
		this.longitude = longitude;
		this.latitude = latitude;
		this.tags = tags;
	}
	
	public double getLongitude()
	{
		return longitude;
	}
	
	public double getLatitude()
	{
		return latitude;
	}
	
	public NodeList getTags()
	{
		return tags;
	}
}