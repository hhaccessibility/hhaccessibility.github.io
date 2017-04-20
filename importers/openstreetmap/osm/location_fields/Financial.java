package osm.location_fields;

import osm.Location;

public class Financial extends LocationField
{
	public Financial()
	{
		super("Is Financial");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String amenity = location.getValueFor("amenity");
	   String shop = location.getValueFor("shop");
	   String office = location.getValueFor("office");
	   String name = location.getValueFor("name").toLowerCase();
	   String [] financialNames = new String[] {"td bank", "hsbc", "cibc",
		"bmo", "scotia", "h&r block", "h&amp;r block",
		"sun life centre", "rbc"};
	   for( String financialName: financialNames)
	   {
		   if (name.equals(financialName))
			   return true;
	   }
		return name.startsWith("bank") || name.endsWith("bank") ||
		name.contains("financial") || name.contains("insurance")
		|| name.contains("brokerage")
		|| amenity.equals("bank") || amenity.equals("atm") ||
		shop.equals("tax") || office.equals("tax_advisor");
	}
}