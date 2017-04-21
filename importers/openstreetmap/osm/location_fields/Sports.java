package osm.location_fields;

import osm.Location;

public class Sports extends LocationField
{
	public Sports()
	{
		super("Is Sports");
	}

	@Override
	public boolean appliesTo(Location location)
	{
	   String leisure = location.getValueFor("leisure");
	   String sport = location.getValueFor("sport");
	   String source = location.getValueFor("source");
	   String name = location.getValueFor("name").trim().toLowerCase();
		return !leisure.equals("") || leisure.equals("sports_centre")
			|| leisure.equals("swimming_pool") || leisure.equals("playground")||
				leisure.equals("pitch") || sport.equals("baseball") ||
				name.indexOf("fitness") >= 0 || name.startsWith("sport")
				|| name.contains("footwear");
	}
}