<?php
require_once dirname(__FILE__).'/Episode.php';
require_once dirname(__FILE__).'/Episodes.php';
require_once dirname(__FILE__).'/QueryBuilder.php';
require_once dirname(__FILE__).'/Serie.php';
require_once dirname(__FILE__).'/Series.php';
require_once dirname(__FILE__).'/Thetvdb.php';
require_once dirname(__FILE__).'/../../Xml/Reader.php';

/**
 *Elios_Service_Thetvdb_Adapter
 */
class Elios_Service_Thetvdb_Adapter{
	
	protected static $_instance;
	
	/**
	 * 
	 * API KEY
	 * @var string
	 */
	protected $apiKey;
	
	/**
	 * 
	 * TheTvDb url
	 * @var string
	 */
	protected $serverUri;
	
	/**
	 * 
	 * Xml mirrors
	 * @var array
	 */
	protected $xmlmirrors;
	
	/**
	 * 
	 * Banner mirrors
	 * @var array
	 */
	protected $bannermirrors;
	
	/**
	 * 
	 * Zip mirrors
	 * @var array
	 */
	protected $zipmirrors;

	/**
	 * @var array
	 */
	protected $xmlmasks = array(1,3,5,7);
	
	/**
	 * @var array
	 */
	protected $bannermasks = array(2,6,7);
	
	/**
	 * @var array
	 */
	protected $zipmasks = array(4,7);
	
	
    /**
     * Returns an instance of this class
     * (this class uses the singleton pattern)
     *
     * @return Elios_Service_Thetvdb_Adapter
     */
    public static function getInstance(){
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 
     * Class constructor
     */
    public function __construct(){
    	$this->serverUri = "http://thetvdb.com";
    }
    
	/**
	 *  get api key
	 */
	public function getApiKey(){
		return $this->apiKey;
	}
    
	/**
	 * 
	 * @return a list of available mirrors
	 * @param string $mask
	 */
	public function getAvailableMirror($mask){
		if(in_array($mask,$this->xmlmasks))
			return $this->xmlmirrors[rand(0,sizeof($this->xmlmirrors)-1)]['mirrorpath'];
		else if(in_array($mask, $this->bannermasks))
			return $this->bannermirrors[rand(0,sizeof($this->bannermirrors)-1)]['mirrorpath'];
		else if(in_array($mask, $this->zipmasks))
			return $this->zipmirrors[rand(0,sizeof($this->zipmirrors)-1)]['mirrorpath'];
	}
	
	
	/**
	 * Set mirrors
	 */
	public function setMirors(){
		
		$XmlReader = new Elios_Xml_Reader();
		$XmlReader->loadXml($this->serverUri."/api/".$this->apiKey."/mirrors.xml");
		$mirrors = $XmlReader->getElements("Mirror");

		foreach($mirrors as $e){
			$mirror = array();
			foreach ($e->childNodes as $node){
				switch ($node->nodeName) {
					case "id":
						$mirror['id'] = $node->nodeValue;
					break;
					
					case "mirrorpath":
						$mirror['mirrorpath'] = $node->nodeValue;
					break;
					
					case "typemask":
						$mirror['typemask'] = $node->nodeValue;
					break;
					
				} 	
			}

			if(in_array($mirror['typemask'],$this->xmlmasks)){
				$this->xmlmirrors[] = $mirror;
			}
			
			if(in_array($mirror['typemask'],$this->bannermasks)) {
				$this->bannermirrors[] = $mirror;
			}
			
			if(in_array($mirror['typemask'],$this->zipmasks)){
				$this->zipmirrors[] = $mirror;
			}
		}
	}
	
	
	/**
	 *  set api key
	 *  @param string
	 */
	public function setApiKey($string){
		$this->apiKey = $string;
	}
		
	/**
	 * 
	 * Returns Mixed: xml document with loaded elements/ZIP file
	 * @param Elios_Service_Thetvdb_QueryBuilder $query
	 */
	public function executeQuery($query){
		//@todo check user rights
		if(strpos($query->getQueryAsString(),'.zip')){
			//put zip file in cache folder
			$dirname = md5(rand(1,time()));
			mkdir(dirname(__FILE__).'/cache/'.$dirname, 0770);
			$file = fopen(dirname(__FILE__).'/cache/'.$dirname.'/file.zip', 'a+');
			fwrite($file, file_get_contents($query->getQueryAsString()));
			fclose($file);
			
			$zip = new ZipArchive;
			if($zip->open(dirname(__FILE__).'/cache/'.$dirname.'/file.zip')) {
				$zip->extractTo(dirname(__FILE__).'/cache/'.$dirname);
				$files = array();
				for( $i = 0; $i < $zip->numFiles; $i++ ){ 
				    $c = $zip->statIndex( $i ); 
				    $files[] = basename( $c['name'] ); 
				} 
				$zip->close();
				
				$XmlReader = new Elios_Xml_Reader();
				$xmlFiles = array();
				$xmlFiles['dir'] = dirname(__FILE__).'/cache/'.$dirname;
				foreach($files as $f){
					if($query->select == '*')
						$xmlFiles[$f] = dirname(__FILE__).'/cache/'.$dirname.'/'.$f;
					else {
						//@todo
					}	
				}
				unlink(dirname(__FILE__).'/cache/'.$dirname.'/file.zip');
				return $xmlFiles;
			} else {
				//@todo throw exeception
				return false;
			}
		} else {
			$XmlReader = new Elios_Xml_Reader();
			$XmlReader->loadXml($query->getQueryAsString());
			//print_r($XmlReader->getXml());
			if($query->select == '*'){
				return $XmlReader;
			} else {
				return $XmlReader->getElements($query->select);	
			}
		}
	}	
}