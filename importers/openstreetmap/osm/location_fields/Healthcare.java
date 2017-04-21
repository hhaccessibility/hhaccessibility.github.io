package osm.location_fields;

import osm.Location;

public class Healthcare extends LocationField
{
	public Healthcare()
	{
		super("Is Healthcare");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String name = location.getValueFor("name").toLowerCase();
	   String amenity = location.getValueFor("amenity").toLowerCase();
	   String emergency = location.getValueFor("emergency").toLowerCase();
	   String []healthcareSubstrings = new String[]{"hospital", "clinic",
		"pharma", "medical", "medicine", "diet", "health",
		"drugs", "drugmart", "care ",
		"drug mart", "optom", "pediatric",
		"dentist", "dental", "rexall", "i.d.a.", "physician",
		"doctor", "surgeon", "wellness"};
		for( String healthcareSubstring: healthcareSubstrings)
		{
			if (name.contains(healthcareSubstring))
				return true;
		}
		return amenity.equals("hospital") || amenity.equals("clinic") ||
		amenity.equals("pharmacy") || emergency.equals("yes");
	}
}