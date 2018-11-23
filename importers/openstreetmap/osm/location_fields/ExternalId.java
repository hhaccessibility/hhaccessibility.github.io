package osm.location_fields;

import osm.Location;

public class ExternalId extends LocationField
{
	public ExternalId()
	{
		super("ID");
	}

	@Override
	public String getValueFor(Location location)
	{
		return location.getId();
	}
}