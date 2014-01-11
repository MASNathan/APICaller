<?php

namespace MASNathan\Geoplugin;
use MASNathan\APIcaller\APIcaller;

/**
 * Geoplugin - API Wrapper for http://www.geoplugin.com/, Simple example on How to use the APIcaller class to call an API
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @version 0.1.2
 */
class Geoplugin extends APIcaller
{
	public function __construct()
	{
		parent::__construct('http://www.geoplugin.net/', APIcaller::METHOD_GET, 'json');
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
		
		$data = $this -> call('json.gp', $params);
		
		if ($renameArrayKeys) {
			$tmpData = array();
			foreach ($data as $key => $value) {
				$tmpData[str_replace('geoplugin_', '', $key)] = $value;
			}
			$data = $tmpData;
		}

		return $data;
	}
}
