package osm.location_fields;

import osm.Location;

public class PhoneNumber extends LocationField
{
	public PhoneNumber()
	{
		super("Phone Number");
	}
	
	@Override
	public String getValueFor(Location location)
	{
		String[] phoneKeys = new String[]{"phone"};
		for (String phoneKey: phoneKeys)
		{
			String phone = location.getValueFor(phoneKey);
			if ( !phone.trim().equals("") )
				return phone;
		}
		return "";
	}
}