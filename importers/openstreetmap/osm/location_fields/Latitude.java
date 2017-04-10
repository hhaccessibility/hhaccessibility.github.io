package osm.location_fields;

import osm.Location;

public class Latitude extends LocationField
{
	public Latitude()
	{
		super("Latitude");
	}
	
	@Override
	public String getValueFor(Location location)
	{
		return "" + location.getLatitude();
	}
}