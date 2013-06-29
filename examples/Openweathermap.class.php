<?php

require_once '../APIcaller.class.php';

/**
 * Openweathermap - API Wrapper for http://openweathermap.org/, Simple example on How to use the APIcaller class to call an API
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller/blob/master/examples/Openweathermap.class.php github repository
 * @version 0.1.0 - 26-06-2013 21:06:00
 *     - release into the wild
 * 
 */
class Openweathermap extends APIcaller
{
	private static $_me = null;
	
	function __construct( $apiid = '' )
	{
		parent::__construct();
		
		$this -> setMethod( 'GET' );
		$this -> setUrl( 'http://api.openweathermap.org/data/2.5/' );
		//Default values will be always added to the URL
		if ( $apiid )
			$this -> setDefault( 'APPID', 	$apiid );
		$this -> setDefault( 'mode', 	'json' );
		
		self::$_me = $this;
	}
	
	/**
	 * Returns itself
	 * @return obj
	 */
	static public function getInstance()
	{
		if ( !self::$_me instanceof Openweathermap )
			self::$_me = new self();
		
		return self::$_me;
	}
	
	/**
	 * Sets the default language
	 * @param string $lang
	 * @return obj Returns itself
	 */
	public function setLanguage( $lang )
	{
		$this -> setDefault( 'lang', $lang );
		return self::$_me;
	}
	
	/**
	 * Sets the default units system
	 * @param string $lang It can be either "internal", "metric" or "imperial"
	 * @return obj Returns itself
	 */
	public function setUnits( $units )
	{
		$this -> setDefault( 'units', $units );
		return self::$_me;
	}
	
	/**
	 * Seaching current weather by city name
	 * @param string $cityname
	 * @return array
	 */
	public function getCurrentWeatherByCity( $cityName )
	{
		$params = array(
			'q'	=> $cityName
		);
		return $this -> call( 'weather', $params );
	}
	
	/**
	 * Seaching current weather by geographic coordinats
	 * @param double $lat
	 * @param double $lon
	 * @return array
	 */
	public function getCurrentWeatherByCoordinats( $lat, $lon )
	{
		$params = array(
			'lat'	=> $lat,
			'lon'	=> $lon,
		);
		return $this -> call( 'weather', $params );
	}
		
	/**
	 * Seaching current weather by city ID
	 * @param integer $id
	 * @return array
	 */
	public function getCurrentWeatherByID( $id )
	{
		$params = array(
			'id'	=> $id
		);
		return $this -> call( 'weather', $params );
	}
	
	/**
	 * Seaching forecast by city name
	 * @param string $cityname
	 * @param integer $numberOfDays
	 * @return array
	 */
	public function getForecastByCity( $cityName, $numberOfDays = 1 )
	{
		$params = array(
			'q'		=> $cityName,
			'cnt'	=> $numberOfDays
		);
		return $this -> call( 'forecast', $params );
	}
	
	/**
	 * Seaching forecast by geographic coordinats
	 * @param double $lat
	 * @param double $lon
	 * @param integer $numberOfDays
	 * @return array
	 */
	public function getForecastByCoordinats( $lat, $lon, $numberOfDays = 1 )
	{
		$params = array(
			'lat'	=> $lat,
			'lon'	=> $lon,
			'cnt'	=> $numberOfDays
		);
		return $this -> call( 'forecast', $params );
	}
		
	/**
	 * Seaching forecast by city ID
	 * @param integer $id
	 * @param integer $numberOfDays
	 * @return array
	 */
	public function getForecastByID( $id, $numberOfDays = 1 )
	{
		$params = array(
			'id'	=> $id,
			'cnt'	=> $numberOfDays
		);
		return $this -> call( 'forecast', $params );
	}
	
	
	/**
	 * Seaching forecast by city name
	 * @param string $cityname
	 * @param integer $numberOfDays
	 * @param string $type It can be either "like" or "accurate"
	 * @return array
	 */
	public function findByCity( $cityName, $numberOfDays = 1, $type = '' )
	{
		$params = array(
			'q'		=> $cityName,
			'cnt'	=> $numberOfDays,
			'type'	=> $type,
		);
		return $this -> call( 'forecast', $params );
	}
	
	/**
	 * Seaching forecast by geographic coordinats
	 * @param double $lat
	 * @param double $lon
	 * @param integer $numberOfDays
	 * @param string $type It can be either "like" or "accurate"
	 * @return array
	 */
	public function findByCoordinats( $lat, $lon, $numberOfDays = 1, $type = '' )
	{
		$params = array(
			'lat'	=> $lat,
			'lon'	=> $lon,
			'cnt'	=> $numberOfDays,
			'type'	=> $type,
		);
		return $this -> call( 'forecast', $params );
	}
}

