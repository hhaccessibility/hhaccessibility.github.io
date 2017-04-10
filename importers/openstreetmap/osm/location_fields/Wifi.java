package osm.location_fields;

import osm.Location;

public class Wifi extends LocationField
{
	public Wifi()
	{
		super("Is Wifi");
	}

	@Override
	public boolean appliesTo(Location location)
	{
		String [] wifiProperties = new String[] { "wifi",
			"internet_access:fee", "internet_access"};
		for (String key: wifiProperties)
		{
			if ( !location.getValueFor(key).equals("") )
				return true;
		}
		return false;
	}
}