<?php

require_once '../APIcaller.class.php';

/**
 * Geoplugin - API Wrapper for http://www.geoplugin.com/, Simple example on How to use the APIcaller class to call an API
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller/blob/master/examples/Geoplugin.class.php
 * @version 0.1.0 - 26-06-2013 21:31:23
 *     - release into the wild
 */
class Geoplugin extends APIcaller
{
	private static $_me = null;
	
	function __construct( $apiid = '' )
	{
		parent::__construct();
		
		$this -> setMethod( 'GET' );
		$this -> setUrl( 'http://www.geoplugin.net/' );
		
		self::$_me = $this;
	}
	
	/**
	 * Returns itself
	 * @return obj
	 */
	static public function getInstance()
	{
		if ( !self::$_me instanceof Geoplugin )
			self::$_me = new self();
		
		return self::$_me;
	}
	
	/**
	 * Fetches the IP locatio info
	 * @param string $ip
	 * @param string $baseCurrency i.e.: "EUR"
	 * @return array
	 */
	public function getLocation( $ip = '', $baseCurrency = '', $renameArrayKeys = false )
	{
		$params = array(
			'ip'	=> !$ip ? $_SERVER['REMOTE_ADDR'] : $ip,
			'base_currency'	=> $baseCurrency,
		);
		
		$data = $this -> call( 'json.gp', $params );
		
		if ( !$renameArrayKeys )
			return $data;
		
		$tmpData = array();
		foreach ( $data as $key => $value )
			$tmpData[ str_replace( 'geoplugin_', '', $key ) ] = $value;
		
		return $tmpData;
	}
}

