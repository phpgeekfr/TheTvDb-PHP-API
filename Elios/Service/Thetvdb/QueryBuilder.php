<?php

/**
 * Elios_Service_Thetvdb_QueryBuilder
 */
class Elios_Service_Thetvdb_QueryBuilder{
	
	/**
	 * Query Select element
	 * @var string
	 */
	public $select;
	
	/**
	 * Query From element
	 * @var string
	 */
	public $from;
	
	/**
	 * Query Where element
	 * @var string
	 */
	public $where;
	
	/**
	 * Mirror type for the current query
	 * @var string
	 */
	protected $mirrortype;
	
	/**
	 * @var Elios_Service_Thetvdb_Adapter
	 */
	protected $adapter;
	
	/**
	 * Api KEY
	 * @var string
	 */
	public $apikey;
	
	/**
	 * @var string
	 */
	const APIKEY = 'APIKEY';
	
	/**
	 * @var string
	 */
	const XMLMASK = 1;
	
	/**
	 * @var string
	 */
	const BANNERMASK = 2;
	
	/**
	 * @var string
	 */
	const ZIPMASK = 4;
	
	/**
	 * Class Constructor
	 */
	function __construct() {
       $this->adapter = Elios_Service_Thetvdb_Adapter::getInstance();
       $this->apikey = $this->adapter->getApiKey();
       $this->where = array();
       //@todo limit setMirrors calls
       $this->adapter->setMirors();
       $this->mirrortype = self::XMLMASK;
    }
	
	/**
	 * @param $select string
	 */
	public function select($select){
		$this->select = $select;
	}
	
	/**
	 * @param string $from
	 * @param string $mask
	 * @param string mask
	 */
	public function from($from,$mask = self::XMLMASK){
		$this->from = str_replace(self::APIKEY,$this->apikey,$from);
		$this->mirrortype = $mask;
	}
	
	/**
	 * 
	 * @param string $where
	 */
	public function where($where){
		$this->where = $where;
	}
	
	/**
	 * execute query in adapter
	 */
	public function execute(){
		return $this->adapter->executeQuery($this);
	}
	
	/**
	 * 
	 * Returns the query as string
	 */
	public function getQueryAsString(){
		$where = '';
		$i = 0;
		foreach($this->where as $k=>$w){
			if($i==0)
				$where ='?'.$k.'='.$w;
			else
				$where .='&'.$k.'='.$w;
			$i++;
		} 
		
		return $this->getAvailableMirror().$this->from.$where;
	}
	
	/**
	 * Get availiable Mirrors
	 * @return array
	 */
	public function getAvailableMirror(){
		return $this->adapter->getAvailableMirror($this->mirrortype);
	}
}