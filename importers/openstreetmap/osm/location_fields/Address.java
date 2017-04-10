package osm.location_fields;

import osm.Location;

public class Address extends LocationField
{
	public Address()
	{
		super("Address");
	}
	
	@Override
	public String getValueFor(Location location)
	{
		String postalCode = location.getValueFor("addr:postcode");
		String street = location.getValueFor("addr:street");
		String city = location.getValueFor("addr:city");
		String province = location.getValueFor("addr:province");
		String country = location.getValueFor("addr:country");
		String houseNumber = location.getValueFor("addr:housenumber");
		// For consistency's sake, use Windsor instead of City of Windsor.
		if ( city.equals("City of Windsor") )
			city = "Windsor";
		if ( province.equals("") )
			province = location.getValueFor("addr:state");

		String address = (postalCode + " " + houseNumber + " " + street + " " + city).trim();
		if ( !province.equals("") ) {
			if ( !address.equals("") )
				address = address + ", ";

			address += province;
		}
		return address;
	}
}