package osm.location_fields;

import osm.Location;

public class Accomodation extends LocationField
{
	public Accomodation()
	{
		super("Is Accomodation");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String barrier = location.getValueFor("barrier");
	   String tourism = location.getValueFor("tourism");
		return tourism.equals("hotel") || tourism.equals("motel") || tourism.equals("hostel")
		|| tourism.equals("bnb");		
	}
}