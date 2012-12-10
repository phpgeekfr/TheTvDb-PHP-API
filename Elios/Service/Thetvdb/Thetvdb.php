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
	 * @deprecated use: updateRecords method instead
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
	
	/**
	 * return all updates for the specified timeFrame.
	 * episodes are excluded with param $timeFrame set to "all"
	 * @see http://thetvdb.com/wiki/index.php/API:Update_Records
	 * @param $timeFrame : day, week, month, all
	 * @return array
	 */
	public function updateRecords($timeFrame ='day')
	{
		$query = new Elios_Service_Thetvdb_QueryBuilder();
		$query->select("Data");
		$query->from("/api/".Elios_Service_Thetvdb_QueryBuilder::APIKEY."/updates/updates_".$timeFrame.".xml");
		$query->where(array());
		$items = $query->execute();

		$data = array();
		$data['series'] = array();
		$data['episodes']= array();
		$data['banners']= array();
		$data['time']= null;
		
		foreach($items as $e){
			if($e->hasAttributes()){
				$attributes = $e->attributes;
				if(!is_null($attributes)){
					foreach ($attributes as $index=>$attr){
						if($attr->name == 'time'){
							$data['time'] =  $attr->value;
                         }
                   	 }
                }
            }
                 
                
			foreach ($e->childNodes as $node){	
				switch ($node->nodeName) {
					case "Banner":
						$banner = array();
						foreach($node->childNodes as $subNode){
							switch ($subNode->nodeName) {
								case "time":
									$banner['time'] = trim($subNode->nodeValue);
								break;
								case "Series":
									$banner['serie_id'] = trim($subNode->nodeValue);
								break;
								case "format":
									$banner['format'] = trim($subNode->nodeValue);
								break;
								case "language":
									$banner['language'] = trim($subNode->nodeValue);
								break;
								case "path":
									$banner['path'] = trim($subNode->nodeValue);
								break;
								case "type":
									$banner['type'] = trim($subNode->nodeValue);
								break;
								default:
								break;
							}
							
						}

						$data['banners'][] = $banner;
					break;
					
					case "Series":
						$serie = array();
						foreach($node->childNodes as $subNode){
							switch ($subNode->nodeName) {
								case "time":
									$serie['time'] = trim($subNode->nodeValue);
								break;
								case "id":
									$serie['id'] = trim($subNode->nodeValue);
								break;
								default:
								break;
							}
							
						}
						$data['series'][] = $serie;
					break;
					
					case "Episode":
						$episode = array();
						foreach($node->childNodes as $subNode){
							switch ($subNode->nodeName) {
								case "time":
									$episode['time'] = trim($subNode->nodeValue);
								break;
								case "id":
									$episode['id'] = trim($subNode->nodeValue);
								break;
								case "Series":
									$episode['serie_id'] = trim($subNode->nodeValue);
								break;
								default:
								break;
							}
							
						}
						$data['episodes'][] = $episode;
					break;
					
					default:
					break;
				}
			}
		}
		return $data;
	}
}