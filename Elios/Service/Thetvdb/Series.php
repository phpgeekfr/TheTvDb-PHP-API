<?php

/**
 * Elios_Service_Thetvdb_Series
 */
class Elios_Service_Thetvdb_Series{
	
	/**
	 * Collection of Elios_Service_Thetvdb_Serie
	 * @var Array
	 */
	public $_collection;
	
	/**
	 * 
	 * Return a collection of Elios_Service_Thetvdb_Serie
	 * @param string $name
	 * @param string $lang language shortcode
	 */
	public function findByName($name,$lang = "en"){
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		$query->select("Series");
		$query->from("/api/GetSeries.php");
		$query->where(array(
						"seriesname" => $name,
						"language" => $lang
					));
		$series = $query->execute();			
		
		foreach($series as $e){
			$thetvdb_Serie = new Elios_Service_Thetvdb_Serie($e);	
			$this->_collection[] = $thetvdb_Serie;
		}
    	return $this->_collection;	
	}
	
	/**
	 * @return Elios_Service_Thetvdb_Serie
	 * @param integer $thetvdb_id
	 * @param lang $lang
	 */
	public function getSerie($thetvdb_id ,$lang = 'en'){
		$serie = new Elios_Service_Thetvdb_Serie();
		$serie->language = $lang;
		$serie->thetvdb_id = $thetvdb_id;
		$serie->populate();
		return $serie;
	}
}