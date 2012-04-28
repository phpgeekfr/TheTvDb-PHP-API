<?php

/**
 * Elios_Service_Thetvdb_Episodes
 */
class Elios_Service_Thetvdb_Episodes{
	
	/**
	 * 
	 * @return Elios_Service_Thetvdb_Episode 
	 * @param integer $thetvdb_id of episode
	 * @param lang $lang language shortcode
	 */
	public function getEpisode($thetvdb_id ,$lang = 'en'){
		$episode = new Elios_Service_Thetvdb_Episode();
		$episode->language = $lang;
		$episode->thetvdb_id = $thetvdb_id;
		$episode->populate();
		return $episode;
	}
	
}