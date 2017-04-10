package osm.location_fields;

import osm.Location;

public class PublicService extends LocationField
{
	public PublicService()
	{
		super("Is Public Service");
	}

	@Override
	public boolean appliesTo(Location location)
	{
	   String pservice = location.getValueFor("amenity");
	   String ppublic = location.getValueFor("public");
	   String tourism = location.getValueFor("tourism");
	   String service = location.getValueFor("service");
	   
		return pservice.equals("police") || pservice.equals("social_facility")
				|| tourism.equals("information")
				|| !ppublic.equals("") || pservice.equals("fire_station");
	}
}