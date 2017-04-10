package osm.location_fields;

import osm.Location;

public class URLField extends LocationField
{
	public URLField()
	{
		super("URL");
	}
	
	@Override
	public String getValueFor(Location location)
	{
		String url = location.getValueFor("website");

		// a little sanitization
		if ( !url.equals("") && !url.toLowerCase().startsWith("http:") 
			&& !url.toLowerCase().startsWith("https:") ) {
			url = "http://" + url;
		}
		return url;
	}
}