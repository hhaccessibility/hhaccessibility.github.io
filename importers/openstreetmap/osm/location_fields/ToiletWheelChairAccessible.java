package osm.location_fields;

import osm.Location;

public class ToiletWheelChairAccessible extends LocationField
{
	public ToiletWheelChairAccessible()
	{
		super("Is Toilet Wheelchair Accessible");
	}

	@Override
	public boolean appliesTo(Location location)
	{
		String toiletWheelchair = location.getValueFor("toilets:wheelchair");
		return toiletWheelchair.equals("yes");
	}
}