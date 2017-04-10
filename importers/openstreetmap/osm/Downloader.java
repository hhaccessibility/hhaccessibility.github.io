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
	
	public static void main(String a[]) throws IOException
	{
		if ( a.length < 3 )
		{
			System.out.println("3 parameters are required but you specified " + a.length);
			return;
		}
		double longitude = Double.parseDouble(a[0]);
		double latitude = Double.parseDouble(a[1]);
		String name = a[2];
		double delta = 0.03;
		String url = "http://overpass.osm.rambler.ru/cgi/xapi_meta?*[bbox=" + (longitude - delta) 
		+ "," + (latitude - delta) + "," + (longitude + delta) + "," + (latitude + delta) + "]";
		System.out.println("About to download file.");
		downloadFromURLToFile(url, "raw_xml/box_" + longitude + "_" + latitude + ".xml");
	}
}