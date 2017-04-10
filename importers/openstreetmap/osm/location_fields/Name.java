package osm.location_fields;

import osm.Location;

public class Name extends LocationField
{
	public Name()
	{
		super("Name");
	}
	
	@Override
	public String getValueFor(Location location)
	{
		return location.getValueFor("name");
	}
}