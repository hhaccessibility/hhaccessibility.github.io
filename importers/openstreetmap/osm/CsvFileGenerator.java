package osm;

import java.io.IOException;
import java.io.FileWriter;
import java.util.List;
import java.util.LinkedList;
import osm.location_fields.*;

public class CsvFileGenerator
{
  public static void generateCSV(List<Location> locations, String filename)
	throws IOException
  {
	FileWriter writer = new FileWriter(filename);
	LocationField[] fields = new LocationField[] {
		new Name(), new Longitude(), new Latitude(), new PhoneNumber(),
		new Address(), new URLField(),
		new Restaurant(), new Entertainment(), new Sports(), new Education(),
		new Association(), new PublicService(), new Transportation(),
		new Accommodation(), new Shopping(), new Park(), new Financial(),
		new Healthcare(), new WheelChairAccessible(),
		new ToiletWheelChairAccessible(), new Wifi(), new FreeWifi()
	};
	List<String> columnTitles = new LinkedList<String>();
	for (LocationField field: fields)
	{
		columnTitles.add(field.getTitle());
	}
	Csv.writeLine(writer, columnTitles);
	String[] fieldValues = new String[fields.length];

	for ( Location location: locations )
	{
		int i = 0;
		for (LocationField field: fields)
		{
			fieldValues[i] = field.getValueFor(location);
			i++;
		}
		Csv.writeValues(writer, fieldValues);
	}

	writer.flush();
	writer.close();
	System.out.println("All locations written to csv file");
  }
}