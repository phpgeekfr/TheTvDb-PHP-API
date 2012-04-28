<?php

/**
 * Elios_Service_Thetvdb_Serie
 */
class Elios_Service_Thetvdb_Serie{
	public $thetvdb_id;
	public $nom;
	public $imdb_id;
	public $language;
	public $overview;				
	public $genre;
	public $lastupdated;
	public $banner;
	public $status;
	private $_epiodes_collection;
	
	public function __construct($xmlData = null){
		if($xmlData != null){		
			$this->build($xmlData);
		}
	}
	
	public function getBaseInformation(){
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		$query->select("*");
		$query->from("/api/".Elios_Service_Thetvdb_QueryBuilder::APIKEY."/series/".$this->thetvdb_id."/all/".$this->language.".zip",Elios_Service_Thetvdb_QueryBuilder::ZIPMASK);
		$data = $query->execute();

		$XmlReader = new Elios_Xml_Reader();
		$dir = $data['dir'];
		unset($data['dir']);
		if(is_array($data)){
			foreach($data as $k=>$d){
				//@todo handle banners & actors files
				if(trim($k) == $this->language.".xml"){
					$XmlReader->loadXml($d);
					$episodes = $XmlReader->getElements("Episode");
					
					//episodes
					foreach($episodes as $e){
						$thetvdb_Episode = new Elios_Service_Thetvdb_Episode($e);	
						$this->_epiodes_collection[] = $thetvdb_Episode;
					}
					
					//serie
					$series = $XmlReader->getElements("Series");
					foreach($series as $s){
						$this->build($s);
					}
				}
				unlink($d);
			}
			rmdir($dir);
			return true;
		} else {
			$this->_epiodes_collection = null;
			return false;
		}
	}
	
	public function build($xmlData = null){
		foreach ($xmlData->childNodes as $node){	
			switch ($node->nodeName) {
				case "seriesid":
					$this->thetvdb_id = trim($node->nodeValue);
				break;
				
				case "SeriesName":
					$this->nom = $node->nodeValue;
				break;
				
				case "Genre":
					$this->genre = $node->nodeValue;
				break;
				
				case "IMDB_ID":
					$this->imdb_id = $node->nodeValue;
				break;
				
				case "language":
					$this->language = $node->nodeValue;
				break;
				
				case "lastupdated":
					$this->lastupdated = $node->nodeValue;
				break;
				
				case "Status":
					$this->status = $node->nodeValue;
				break;
				default:
				break;
			}
		}
	}
	
	public function populate(){
		$this->getBaseInformation();
	}
	
	public function getEpisodes(){
		if($this->_epiodes_collection == null)
			$this->getBaseInformation();
		return $this->_epiodes_collection;
	}
}