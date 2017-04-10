package osm.location_fields;

import osm.Location;

public class FreeWifi extends LocationField
{
	public FreeWifi()
	{
		super("Is Free Wifi");
	}

	@Override
	public boolean appliesTo(Location location)
	{
		String wifi = location.getValueFor("wifi");
		if ( wifi != null && wifi.equals("free") )
			return true;
		
		wifi = location.getValueFor("internet_access:fee");
		if ( wifi != null && wifi.equals("no") )
			return true;
		
		return false;
	}
}