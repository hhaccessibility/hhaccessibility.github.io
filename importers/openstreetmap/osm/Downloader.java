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
	throws MalformedURLException, IOException, InterruptedException
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
				String cacheFilename = "raw_xml/box_" + lon + "_" + lat + ".xml";
				File f = new File(cacheFilename);
				if (!f.exists()) {
					String url = "https://overpass-api.de/api/map?bbox=" 
					+ (lon - partDeltaWithOverlap) 
					+ "," + (lat - partDeltaWithOverlap) + "," + (lon + partDeltaWithOverlap) 
					+ "," + (lat + partDeltaWithOverlap);
					System.out.println("About to download file.");
					downloadFromURLToFile(url, cacheFilename);
					System.out.println("Going to sleep for a few seconds to prevent an HTTP 429 Too Many Requests response");
					Thread.sleep(25000); // sleep for a few seconds to prevent sending too many requests.
					System.out.println("Waking up to continue downloading.");
				}
				else {
					System.out.println("Cache file already exists.");
				}
			}
		}
	}

	private static void downloadFromCoordinatesFile() throws IOException, InterruptedException
	{
		FileInputStream fstream = new FileInputStream("coordinates.txt");
		BufferedReader br = new BufferedReader(new InputStreamReader(fstream));
		String strLine;

		//Read File Line By Line
		while ((strLine = br.readLine()) != null)   {
			String parts[] = strLine.split(",");
			double longitude = Double.parseDouble(parts[1].trim());
			double latitude = Double.parseDouble(parts[0].trim());
			System.out.println("You specified longitude to be " + longitude + " and latitude to be " + latitude);
			if ( latitude < -90 || latitude > 90 )
			{
				System.out.println("The latitude must be between -90 and 90 degrees.  Check that you specified them in the correct order.");
				return;
			}
			double delta = 0.03;
			downloadMultipleParts(longitude, latitude, delta, 2);
		}

		//Close the input stream
		br.close();
	}

	public static void main(String a[]) throws IOException, InterruptedException
	{
		if ( a.length < 2 )
		{
			System.out.println("2 parameters are normally required but they were not provided so using coordinates.txt instead.");
			downloadFromCoordinatesFile();
			return;
		}
		double longitude = Double.parseDouble(a[0].trim());
		double latitude = Double.parseDouble(a[1].trim());
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