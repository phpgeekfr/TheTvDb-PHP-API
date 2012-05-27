<?php

/**
 * Elios_Service_Thetvdb_Banner
 */
class Elios_Service_Thetvdb_Banner{
	public $id;
	public $BannerPath;
	public $BannerType;
	public $BannerType2;
	public $Colors;
	public $Language;
	public $Rating;
	public $RatingCount;
	public $SeriesName;
	public $ThumbnailPath;
	public $VignettePath;
	public $basePath = "http://thetvdb.com/banners/";
	

	public function __construct($xmlData = null){
		if($xmlData != null){		
			$this->build($xmlData);
		}
	}
	
	public function build($xmlData = null){
		if($xmlData != null){		
			foreach ($xmlData->childNodes as $node){
				switch ($node->nodeName) {
					case "id":
						$this->id = trim($node->nodeValue);
					break;
					
					case "BannerPath":
						$this->BannerPath = $node->nodeValue;
					break;
					case "BannerType":
						$this->BannerType = $node->nodeValue;
					break;
					case "BannerType2":
						$this->BannerType2 = $node->nodeValue;
					break;
					case "Colors":
						$this->Colors = addslashes($node->nodeValue);
					break;
					case "Language":
						$this->Language = addslashes($node->nodeValue);
					break;
					case "Rating":
						$this->Rating = $node->nodeValue;
					break;
					case "RatingCount":
						$this->RatingCount = $node->nodeValue;
					break;
					case "SeriesName":
						$this->SeriesName = $node->nodeValue;
					break;
					case "ThumbnailPath":
						$this->ThumbnailPath = $node->nodeValue;
					break;
					case "VignettePath":
						$this->VignettePath = $node->nodeValue;
					break;
					default:
					break;
				}
			}
		}
	}
}