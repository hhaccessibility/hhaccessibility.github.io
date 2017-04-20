package osm.location_fields;

import osm.Location;
import java.util.Arrays;

public class Restaurant extends LocationField
{
	public Restaurant()
	{
		super("Is Restaurant");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
		String cuisine = location.getValueFor("cuisine");
		String amenity = location.getValueFor("amenity");
		String name = location.getValueFor("name").trim().toLowerCase();
		String []restaurantNames = new String[]{"starbucks",
			"tim hortons", "tim horton's", "wendy's", "wendys", "mcdonalds",
			"harveys", "a&w", "a&amp;w", "kfc", "swiss chalet", "taco bell"};
		if ( Arrays.asList(restaurantNames).contains(name) )
			return true;

		return name.endsWith(" bar") || name.endsWith(" pub") ||
			name.endsWith(" tea") || name.endsWith(" coffee") ||
			name.endsWith(" cafe") || name.endsWith(" taco") ||
			name.startsWith("taco ") || name.contains("fried chicken") ||
			!cuisine.equals("") || amenity.equals("fast_food") ||
			amenity.equals("restaurant") || amenity.equals("cafe") || amenity.equals("bar");
	}
}