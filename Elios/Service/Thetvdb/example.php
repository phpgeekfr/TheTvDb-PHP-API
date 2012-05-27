<?php
/*
 * TheTvDb Service Setup
 */
require_once dirname(__FILE__).'/Adapter.php';
$tvdb_adapter = Elios_Service_Thetvdb_Adapter::getInstance();
$tvdb_adapter->setApiKey("APIKEY");

$tvdb = new Elios_Service_Thetvdb_Thetvdb();

/*
 * Get the current mirror
 */
$mirror = $tvdb->getAvailableMirror();

/*
 * Get the current server time
 * store this value in database for future updates
 */
$time = $tvdb->getServerTime();


$seriesT = new Elios_Service_Thetvdb_Series();
/*
 * Search a serie by name
 */
$series = $seriesT->findByName("South Park","en");
foreach($series as $serie){
	echo $serie->nom."&nbsp;".$serie->thetvdb_id.'<br/>';
}

/*
 * Now we use thedvdb_id to get full serie data
 * Elios_Service_Thetvdb_Serie attributes:
 * 
 * public $thetvdb_id;
 * public $nom;
 * public $imdb_id;
 * public $language;
 * public $overview;				
 * public $genre;
 * public $lastupdated;
 * public $banner;
 * public $status; 
*/
$serie = $seriesT->getSerie("75897","en");
echo $serie->nom.'<br/>';


/*
 * Use $serie->getBanners() to get all banners for the serie.
 * This method returns an array of Elios_Service_Thetvdb_Banner objects.
 * Elios_Service_Thetvdb_Banner attributes:
 * 	public $id;
 *  public $BannerPath;
 *  public $BannerType;
 *  public $BannerType2;
 *  public $Colors;
 *  public $Language;
 *  public $Rating;
 *  public $RatingCount;
 *  public $SeriesName;
 *  public $ThumbnailPath;
 *  public $VignettePath;
 *  public $basePath = "http://thetvdb.com/banners/";
 */

$banners = $serie->getBanners();


/*
 * We can now retrieve all the episodes with $serie->getEpisodes()
 * This method returns an array of Elios_Service_Thetvdb_Episode objects.
 * Elios_Service_Thetvdb_Episode attributes:
 * 
 * public $thetvdb_id;
 * public $saison;
 * public $saison_id;
 * public $serie_id;
 * public $language;
 * public $episode;
 * public $overview;
 * public $lastupdated;
 * public $firstAired;
 * public $titre;
 */
$episodes = $serie->getEpisodes();


/*
 * Use this function to get a list of updated items since your last update
 */
$data = $tvdb->getUpdateList($time);
print_r($data);

/*
 * for each items of the $data array:
 * use $episodeT->getEpisode('ID') && $seriesT->getSerie("ID","LANG") to retreive and store informations 
 */
$episodeT = Elios_Service_Thetvdb_Episodes();
$episode = $episodeT->getEpisode('ID');
$serie = $seriesT->getSerie("ID","LANG");
