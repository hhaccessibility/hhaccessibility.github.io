package osm.location_fields;

import osm.Location;

public class Shopping extends LocationField
{
	public Shopping()
	{
		super("Is Shopping");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String shop = location.getValueFor("shop");
	   String amenity = location.getValueFor("amenity");
	   String name = location.getValueFor("name").toLowerCase();
		return !shop.equals("") || amenity.equals("bicycle_repair_station") ||
			name.equals("market") || name.endsWith(" market");
	}
}