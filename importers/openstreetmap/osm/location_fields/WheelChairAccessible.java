package osm.location_fields;

import osm.Location;

public class WheelChairAccessible extends LocationField
{
	public WheelChairAccessible()
	{
		super("Is Wheelchair Accessible");
	}

	@Override
	public boolean appliesTo(Location location)
	{
		String wheelchair = location.getValueFor("wheelchair");
		return wheelchair.equals("yes");
	}
}