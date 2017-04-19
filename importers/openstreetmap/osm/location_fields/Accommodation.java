package osm.location_fields;

import osm.Location;

public class Accommodation extends LocationField
{
	public Accommodation()
	{
		super("Is Accommodation");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String barrier = location.getValueFor("barrier");
	   String tourism = location.getValueFor("tourism");
	   String name = location.getValueFor("name").trim();
		return tourism.equals("hotel") || tourism.equals("motel")
		|| tourism.equals("inn")
		|| tourism.equals("hostel")
		|| tourism.equals("bnb") || name.contains("Motor Inn")
		|| name.contains("Inn Suite") || name.contains("Hotel") 
		|| name.contains("Motel") || name.contains("Hostel");
	}
}