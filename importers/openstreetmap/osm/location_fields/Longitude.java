package osm.location_fields;

import osm.Location;

public class Longitude extends LocationField
{
	public Longitude()
	{
		super("Longitude");
	}
	
	@Override
	public String getValueFor(Location location)
	{
		return "" + location.getLongitude();
	}
}