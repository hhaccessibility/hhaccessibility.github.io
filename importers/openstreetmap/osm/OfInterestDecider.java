package osm;

import java.util.Arrays;

public class OfInterestDecider
{
	public static boolean isLocationOfInterest(Location location)
	{
		if ( location.getValueFor("name").trim().equals("") )
			return false;

		String [] mustBeEmpty = new String[]{
			"surveillance", "bicycle_parking", "railway", "highway", "boundary"};
		for (String key: mustBeEmpty)
		{
			if ( !location.getValueFor(key).equals("") )
				return false;
		}
		if ( location.getValueFor("barrier").equals("bollard") ) {
			// filter out notices of road closures.
			return false;
		}
		String amenity = location.getValueFor("amenity");
		
		// don't include parking lots
		if ( amenity.equals("parking") )
			return false;
		
		String name = location.getValueFor("name");
		String[] uninterestingNames = new String[]{
			"compost", "University of Toronto"};
		if( Arrays.asList(uninterestingNames).contains(name) )
			return false;

		// We're not interested in river, stream or lake names.
		String natural = location.getValueFor("natural");
		String []naturalsOfNoInterest = new String[]{
			"water", "pond", "tree", "shingle", "sand", "mud",
			"bare_rock", "saddle", "scree", "fell", "grassland", "heath",
			"scrub", "tree_row", "wood", "valley"}; 
		if( Arrays.asList(naturalsOfNoInterest).contains(natural) )
			return false;
		/*
		Some natural areas could be of interest such as caves, 
		beaches, hot springs, sink holes.
		*/
		
		String waterway = location.getValueFor("waterway");
		String[] uninterestingWaterways = new String[] {
			"stream", "river", "riverbank", "drain", "ditch",
			"weir", "stream_end", "lock_gate", "turning_point", 
			"water_point"};
		if ( Arrays.asList(uninterestingWaterways).contains(waterway) )
			return false;
		
		String place = location.getValueFor("place");
		String[] uninterestingPlaces = new String[] {
			"neighbourhood", "city", "state", "region",
			"province", "district", "county",
			"municipality", "suburb", "continent",
			"village", "town", "island", "city_block"
		};
		if ( Arrays.asList(uninterestingPlaces).contains(place) )
			return false;
		
		return true;
	}
}