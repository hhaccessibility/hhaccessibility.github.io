package osm;

import java.net.URL;
import java.net.MalformedURLException;
import java.io.*;
import java.nio.file.StandardCopyOption;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

public class Downloader
{
	private static void downloadFromURLToFile(String url, String filename)
	throws MalformedURLException, IOException
	{
		URL website = new URL(url);
		try (InputStream in = website.openStream()) {
			Files.copy(in, Paths.get(filename), StandardCopyOption.REPLACE_EXISTING);
		}
	}
	
	private static void downloadMultipleParts(double longitude, double latitude, double delta, int numDivisions)
	throws MalformedURLException, IOException
	{
		double partDelta = delta / numDivisions;
		/*
		The 1.03 is slightly greater than 1 so we won't miss locations on the lines between
		rectangles we download from.  5% seemed like a safely large overlap while not 
		hurting efficiency a lot.
		*/
		double partDeltaWithOverlap = partDelta * 1.05 / 2;
		
		for (double lon = longitude - delta; lon < longitude + delta; lon += partDelta)
		{
			for (double lat = latitude - delta; lat < latitude + delta; lat += partDelta)
			{
				String url = "http://overpass.osm.rambler.ru/cgi/xapi_meta?*[bbox=" 
				+ (lon - partDeltaWithOverlap) 
				+ "," + (lat - partDeltaWithOverlap) + "," + (lon + partDeltaWithOverlap) 
				+ "," + (lat + partDeltaWithOverlap) + "]";
				System.out.println("About to download file.");
				downloadFromURLToFile(url, "raw_xml/box_" + lon + "_" + lat + ".xml");
			}
		}
	}
	
	public static void main(String a[]) throws IOException
	{
		if ( a.length < 2 )
		{
			System.out.println("2 parameters are required but you specified " + a.length);
			return;
		}
		double longitude = Double.parseDouble(a[0]);
		double latitude = Double.parseDouble(a[1]);
		System.out.println("You specified longitude to be " + longitude + " and latitude to be " + latitude);
		if ( latitude < -90 || latitude > 90 )
		{
			System.out.println("The latitude must be between -90 and 90 degrees.  Check that you specified them in the correct order.");
			return;
		}
		double delta = 0.03;
		downloadMultipleParts(longitude, latitude, delta, 2);
	}
}