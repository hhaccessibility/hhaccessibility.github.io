package osm.location_fields;

import osm.Location;

public class Education extends LocationField
{
	public Education()
	{
		super("Is Education");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
		String amenity = location.getValueFor("amenity");
		String tourism = location.getValueFor("tourism");
		String office = location.getValueFor("office");
		String building = location.getValueFor("building");
		String name = location.getValueFor("name").toLowerCase();
		String[] educationalNames = new String[]{"hackforge", "school",
			"college", "école", "École", "university", "student", "library", "newspaper", "artwork"};
		String combinedString = amenity + tourism + office + building + name;
	   for (String educationalName: educationalNames)
	   {
		   if ( combinedString.contains(educationalName) )
				return true;
	   }
	   return false;
	}
}