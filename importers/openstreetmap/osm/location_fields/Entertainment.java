package osm.location_fields;

import osm.Location;
import java.util.Arrays;

public class Entertainment extends LocationField
{
	public Entertainment()
	{
		super("Is Entertainment");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String leisure = location.getValueFor("leisure");
	   String amenity = location.getValueFor("amenity");
	   String office = location.getValueFor("office");
	   String[] matchingAmenities = new String[]{"theatre", "fountain", "pub", "bar"};
		return Arrays.asList(matchingAmenities).contains(amenity) || office.equals("newspaper");
	}
}