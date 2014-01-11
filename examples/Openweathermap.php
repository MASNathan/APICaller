<?php

namespace MASNathan\Openweathermap;
use MASNathan\APIcaller\APIcaller;

/**
 * Openweathermap - API Wrapper for http://openweathermap.org/, Simple example on How to use the APIcaller class to call an API
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @version 0.1.2
 */
class Openweathermap extends APIcaller
{
	
	/**
	 * Openweathermap class constructor
	 * @param string $apiid
	 */
	function __construct($apiid = '')
	{
		parent::__construct('http://api.openweathermap.org/data/2.5/', APIcaller::METHOD_GET, 'json');

		//Default values will be always added to the URL
		if ($apiid) {
			$this->setDefaultParameter('APPID', $apiid);
		}
		$this->setDefaultParameter('mode', 'json');
	}
	
	/**
	 * Sets the default language
	 * @param string $lang
	 * @return Openweathermap
	 */
	public function setLanguage($lang)
	{
		$this->setDefaultParameter('lang', $lang);
		return $this;
	}
	
	/**
	 * Sets the default units system
	 * @param string $lang It can be either "internal", "metric" or "imperial"
	 * @return Openweathermap
	 */
	public function setUnits($units)
	{
		$this->setDefaultParameter('units', $units);
		return $this;
	}
	
	/**
	 * Seaching current weather by city name
	 * @param string $cityname
	 * @return array
	 */
	public function getCurrentWeatherByCity($cityName)
	{
		$params = array(
				'q'	=> $cityName
			);
		return $this->call('weather', $params);
	}
	
	/**
	 * Seaching current weather by geographic coordinats
	 * @param double $lat
	 * @param double $lon
	 * @return array
	 */
	public function getCurrentWeatherByCoordinats($lat, $lon)
	{
		$params = array(
				'lat' => $lat,
				'lon' => $lon,
			);
		return $this->call('weather', $params);
	}
		
	/**
	 * Seaching current weather by city ID
	 * @param integer $id
	 * @return array
	 */
	public function getCurrentWeatherByID($id)
	{
		$params = array(
				'id' => $id
			);
		return $this->call('weather', $params);
	}
	
	/**
	 * Seaching forecast by city name
	 * @param string $cityname
	 * @param integer $numberOfDays
	 * @return array
	 */
	public function getForecastByCity($cityName, $numberOfDays = 1)
	{
		$params = array(
				'q'	  => $cityName,
				'cnt' => $numberOfDays
			);
		return $this->call('forecast', $params);
	}
	
	/**
	 * Seaching forecast by geographic coordinats
	 * @param double $lat
	 * @param double $lon
	 * @param integer $numberOfDays
	 * @return array
	 */
	public function getForecastByCoordinats($lat, $lon, $numberOfDays = 1)
	{
		$params = array(
				'lat' => $lat,
				'lon' => $lon,
				'cnt' => $numberOfDays
			);
		return $this->call('forecast', $params);
	}
		
	/**
	 * Seaching forecast by city ID
	 * @param integer $id
	 * @param integer $numberOfDays
	 * @return array
	 */
	public function getForecastByID($id, $numberOfDays = 1)
	{
		$params = array(
				'id'  => $id,
				'cnt' => $numberOfDays
			);
		return $this->call('forecast', $params);
	}
	
	
	/**
	 * Seaching forecast by city name
	 * @param string $cityname
	 * @param integer $numberOfDays
	 * @param string $type It can be either "like" or "accurate"
	 * @return array
	 */
	public function findByCity($cityName, $numberOfDays = 1, $type = '')
	{
		$params = array(
				'q'	   => $cityName,
				'cnt'  => $numberOfDays,
				'type' => $type,
			);
		return $this->call('forecast', $params);
	}
	
	/**
	 * Seaching forecast by geographic coordinats
	 * @param double $lat
	 * @param double $lon
	 * @param integer $numberOfDays
	 * @param string $type It can be either "like" or "accurate"
	 * @return array
	 */
	public function findByCoordinats($lat, $lon, $numberOfDays = 1, $type = '')
	{
		$params = array(
				'lat'  => $lat,
				'lon'  => $lon,
				'cnt'  => $numberOfDays,
				'type' => $type,
			);
		return $this->call('forecast', $params);
	}
}
