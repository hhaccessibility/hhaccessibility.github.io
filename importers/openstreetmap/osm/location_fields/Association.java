package osm.location_fields;

import osm.Location;

public class Association extends LocationField
{
	public Association()
	{
		super("Is Association");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String religion = location.getValueFor("religion");
	   String historic = location.getValueFor("historic");
	   String amenity = location.getValueFor("amenity");
	   String name = location.getValueFor("name").toLowerCase();
	   String denomination = location.getValueFor("denomination").toLowerCase();
		return !religion.equals("") || amenity.equals("place_of_worship")
		|| historic.equals("memorial") || historic.equals("monument") || name.contains("holy")
		|| !denomination.equals("");
	}
}