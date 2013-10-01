<?php

require_once '../APIcaller.php';

/**
 * Geoplugin - API Wrapper for http://www.geoplugin.com/, Simple example on How to use the APIcaller class to call an API
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller/blob/master/examples/Geoplugin.class.php
 * @version 0.1.1 - now supports the 0.2.0 version of APIcaller
 *          0.1.0 - release into the wild
 */
class Geoplugin extends APIcaller
{

	function __construct()
	{
		parent::__construct(
			'http://www.geoplugin.net/', 
			APIcaller::METHOD_GET,
			APIcaller::CONTENT_TYPE_JSON
		);
	}
	
	/**
	 * Fetches the IP locatio info
	 * @param string $ip
	 * @param string $baseCurrency i.e.: "EUR"
	 * @return array
	 */
	public function getLocation($ip = '', $baseCurrency = '', $renameArrayKeys = false)
	{
		$params = array(
			'ip'            => !$ip ? $_SERVER['REMOTE_ADDR'] : $ip,
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

