package osm.location_fields;

import osm.Location;

/**
Represents a location field such as "Is Education".  This would be a column in the generated spreadsheet.

*/
public abstract class LocationField
{
	private String title;

	/**
	Initializes a LocationField
	@param title is the title of the column that will appear in the CSV file/spreadsheet.
	*/
	public LocationField(String title)
	{
		this.title = title;
	}
	
	public String getTitle()
	{
		return title;
	}
	
	public boolean appliesTo(Location location)
	{
		return false;
	}
	
	public String getValueFor(Location location)
	{
		return "" + appliesTo(location);
	}
}