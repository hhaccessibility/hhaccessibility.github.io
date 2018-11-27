package osm;

import java.util.List;
import java.util.LinkedList;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;


public class NodeProcessor
{
	/**
	Retrieves all locations from the document out of node elements.

	This does not extract any information from way elements.  
	way elements is the responsibility of WayProcessor.
	*/
  public static List<Location> getLocationsFromNodes(Document doc)
  {
	NodeList nList = doc.getElementsByTagName("node");
	LinkedList<Location> result = new LinkedList<Location>();
	for (int temp = 0; temp < nList.getLength(); temp++) {
		Node nNode = nList.item(temp);
		if (nNode.getNodeType() == Node.ELEMENT_NODE) {
			Element eElement = (Element) nNode;
			NodeList tags = eElement.getElementsByTagName("tag");
			Location newLocation = new Location(
				Double.parseDouble(eElement.getAttribute("lon")),
				Double.parseDouble(eElement.getAttribute("lat")), tags,
				eElement.getAttribute("id"));

			if ( OfInterestDecider.isLocationOfInterest(newLocation) )
			{
				result.add(newLocation);
			}
		}
	}
	return result;
  }
}