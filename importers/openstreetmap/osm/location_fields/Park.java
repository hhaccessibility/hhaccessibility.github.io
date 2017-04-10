package osm.location_fields;

import osm.Location;

public class Park extends LocationField
{
	public Park()
	{
		super("Is Park");
	}

	@Override
	public boolean appliesTo(Location location)
	{
	   String leisure = location.getValueFor("leisure");
	   String amenity = location.getValueFor("amenity");

		return amenity.equals("fountain") ||
			leisure.equals("park") || leisure.equals("garden") ||
			leisure.equals("water_park");
	}
}