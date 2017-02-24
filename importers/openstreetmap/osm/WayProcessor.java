package osm;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.util.*;

/**
WayProcessor is responsible for collecting information on named locations 
from way elements in OpenStreetMap documents.

*/
public class WayProcessor
{
	private static HashMap<String, Element> getIdToNodeMap(Document document)
	{
		NodeList nodes = document.getElementsByTagName("node");
		HashMap<String, Element> idToNodes = new HashMap<String, Element>();
		for (int i = 0; i < nodes.getLength(); i++)
		{
			Element node = (Element)nodes.item(i);
			// if node has all the information we need, include it.
			if ( node.hasAttribute("lon") && node.hasAttribute("lat") && node.hasAttribute("id") ) {
				idToNodes.put(node.getAttribute("id"), node);
			}
		}
		return idToNodes;
	}

	/**
	Returns a list of node Elements referenced by nds by looking them up in nodes.
	
	@param nds is a list of nd elements that reference other nodes.
	@param nodes is a map from node id to node for all nodes in a document.
	*/
	private static List<Element> getReferencedNodes(HashMap<String, Element> nodes, NodeList nds)
	{
		LinkedList<Element> result = new LinkedList<Element>();
		for (int i = 0 ; i < nds.getLength(); i++ )
		{
			result.add(nodes.get(((Element)nds.item(i)).getAttribute("ref")));
		}
		return result;
	}
	
	/**
	Returns average value of the specified attribute through the specified list of elements.
	
	This assumes that the attribute is parsable to double.
	
	@param elements is a bunch of elements to calculate an average over
	@param attrName is an attribute name like "lon" or "lat".
	*/
	private static double getAttributeAverage(List<Element> elements, String attrName)
	{
		double total = 0;
		int size = elements.size();
		if ( size == 0 )
			return 0;

		for (Element e: elements)
		{
			String attrValue = e.getAttribute(attrName);
			double doubleVal = Double.parseDouble(attrValue.trim());
			
			total += doubleVal;
		}
		
		return total / size;
	}
	
	/**
	Returns locations taken from way elements in the specified document
	
	@param document is a document from OpenStreetMap.
	@return a list of all locations extracted from way elements in the specified document
	*/
	public static LinkedList<Location> getLocationsFrom(Document document)
	{
		HashMap<String, Element> nodes = getIdToNodeMap(document);
		NodeList ways = document.getElementsByTagName("way");
		LinkedList<Location> result = new LinkedList<Location>();
		for (int i = 0; i < ways.getLength(); i++ )
		{
			Element way = (Element)ways.item(i);
			NodeList tags = way.getElementsByTagName("tag");
			NodeList nds = way.getElementsByTagName("nd");
			String name = OSM.getValueForKey(tags, "name");

			// if the node has a name and at least 1 reference node, handle it.
			if ( name != null && !name.equals("") && nds.getLength() > 0 )
			{
				List<Element> referencedNodes = getReferencedNodes(nodes, nds);
				double longitude = getAttributeAverage(referencedNodes, "lon");
				double latitude = getAttributeAverage(referencedNodes, "lat");
				result.add(new Location(longitude, latitude, tags));
			}
		}
		System.out.println("Got way elements: " + result.size());
		return result;
	}
}