<?php
class Elios_Xml_Reader{
	
	protected $dom;  
	
	/**
	 * Create a new XmlDocument with given url
	 */
	public function loadXml($documentUrl)
	{
		$this->dom = new DomDocument();
		$this->dom->load($documentUrl);
	}
	
	/**
	 * Returns elements under $tagName node
	 */
	public function getElements($tagName)
	{
		return $this->dom->getElementsByTagName($tagName);
	}
	
	/**
	 * Returns flat xml
	 */
	public function getXml()
	{
		return $this->dom->saveXML();
	}
	
}