package osm.location_fields;

import osm.Location;
import java.util.Arrays;

public class Transportation extends LocationField
{
	public Transportation()
	{
		super("Is Transportation");
	}
	
	@Override
	public boolean appliesTo(Location location)
	{
	   String highway = location.getValueFor("highway").toLowerCase();
	   String barrier = location.getValueFor("barrier").toLowerCase();
	   String railway = location.getValueFor("railway").toLowerCase();
	   String amenity = location.getValueFor("amenity").trim().toLowerCase();
	   String bus = location.getValueFor("bus").toLowerCase();
	   String name = location.getValueFor("name").toLowerCase();
	   String public_transport = location.getValueFor("public_transport");
	   String [] transportationNames = new String[]{
		   "autoshare", "zipcar", "autoexpress"};
	   if ( Arrays.asList(transportationNames).contains(name) ) {
		   return true;
	   }
		return amenity.equals("car_sharing") || amenity.equals("fuel") 
			|| name.startsWith("zipcar") ||
			name.indexOf("airport") >= 0 || name.indexOf("train station") >= 0 ||
			name.indexOf("charter") >= 0 || name.endsWith("transit") || name.startsWith("transit ") ||
			name.startsWith("go transit") || name.contains("taxicab") ||
			name.endsWith(" cab") ||
			name.indexOf("transit station") >= 0 || name.indexOf("bus station") >= 0 || bus.equals("yes")
			|| highway.equals("bus_stop")
			|| !railway.equals("") || !public_transport.equals("");
	}
}