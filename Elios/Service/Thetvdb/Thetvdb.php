<?php

/**
 * Elios_Service_Thetvdb_Thetvdb
 */
class Elios_Service_Thetvdb_Thetvdb {

	/**
	 * Get availiable Mirrors
	 * @return array
	 */
	public function getAvailableMirror(){
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		return $query->getAvailableMirror();
	}
	
	/**
	 * @return current server time
	 */
	public function getServerTime(){
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		$query->select("Time");
		$query->from("/api/Updates.php?type=none");
		$query->getQueryAsString();
		foreach( $query->execute() as $t){
			return  $t->nodeValue; 
		}
	}
	
	/**
	 * 
	 * Retreive updated items since the last update
	 * @param string $lastupdate (timestamp)
	 */
	public function getUpdateList($lastupdate){
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		$query->select("Items");
		$query->from("/api/Updates.php");
		$query->where(array(
						"type" => "all",
						"time" => $lastupdate
					));
		$items = $query->execute();	
		$data = array();
		$data['series'] = array();
		$data['episodes']= array();
		foreach($items as $e){
			foreach ($e->childNodes as $node){	
				switch ($node->nodeName) {
					case "Time":
						$data['time'] = $node->nodeValue;
					break;
					
					case "Series":
						$data['series'][] = trim($node->nodeValue);
					break;
					
					case "Episode":
						$data['episodes'][] = trim($node->nodeValue);
					break;
					
					default:
					break;
				}
			}
		}
		return $data;
		
	}
}