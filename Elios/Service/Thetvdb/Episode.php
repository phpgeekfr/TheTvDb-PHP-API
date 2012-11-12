<?php

/**
 * Elios_Service_Thetvdb_Episode
 */
class Elios_Service_Thetvdb_Episode{
	public $thetvdb_id;
	public $saison;
	public $saison_id;
	public $serie_id;
	public $language;
	public $episode;
	public $overview;
	public $lastupdated;
	public $firstAired;
	public $titre;
	public $imdb_id;
	
	public function __construct($xmlData = null){
		if($xmlData != null){		
			$this->build($xmlData);
		}
	}
	
	public function build($xmlData = null){
		if($xmlData != null){		
			foreach ($xmlData->childNodes as $node){
				//echo $node->nodeName.': '.$node->nodeValue;
				switch ($node->nodeName) {
					case "id":
						$this->thetvdb_id = trim($node->nodeValue);
					break;
					
					case "SeasonNumber":
						$this->saison = $node->nodeValue;
					break;
					case "seasonid":
						$this->saison_id = $node->nodeValue;
					break;
					case "EpisodeNumber":
						$this->episode = $node->nodeValue;
					break;
					case "Overview":
						$this->overview = addslashes($node->nodeValue);
					break;
					case "Language":
						$this->language = addslashes($node->nodeValue);
					break;
					case "lastupdated":
						$this->lastupdated = $node->nodeValue;
					break;
					case "FirstAired":
						$this->firstAired = $node->nodeValue;
					break;
					case "seriesid":
						$this->serie_id = $node->nodeValue;
					break;
					case "IMDB_ID":
						$this->imdb_id = $node->nodeValue;
					break;
					case "EpisodeName":
						$this->titre = $node->nodeValue;
					break;
					default:
					break;
				}
			}
		}
	}
	
	public function getBaseInformation(){
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		$query->select("Episode");
		$query->from("/api/".Elios_Service_Thetvdb_QueryBuilder::APIKEY."/episodes/".$this->thetvdb_id."/".$this->language.".xml");
		$episode = $query->execute();
		foreach($episode as $ep){
			$this->build($ep);
		}
		return true;
	}
	
	public function populate(){
		$this->getBaseInformation();
	}
}