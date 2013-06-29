<?php

require_once '../APIcaller.class.php';

/**
 * @desc 	Geoplugin - API Wrapper for http://www.geoplugin.com/
 * 			Simple example on How to use the APIcaller class to call an API
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @version 0.1.0 - 26-06-2013 21:31:23
 *     - release into the wild
 * 
 * @url https://github.com/ReiDuKuduro/APIcaller/blob/master/examples/Geoplugin.class.php
 */
class ShortURL extends APIcaller
{
	private static $_me = null;
	
	const SHORTENER_TYPE_ADFLY 		= 'adlfy';
	const SHORTENER_TYPE_TINYURL 	= 'tinyurl';
	//const SHORTENER_TYPE_GOOGLE 	= 'google';
	const SHORTENER_TYPE_YOUTUBE 	= 'youtube';
	
	/**
	 * Holds api urls
	 * @var array
	 */
	private $apis = array(
		'adlfy'		=> 'http://api.adf.ly/api.php',
		'tinyurl'	=> 'http://tinyurl.com/api-create.php',
		//'google'	=> 'https://www.googleapis.com/urlshortener/v1/url',
		'youtube'	=> 'http://y2u.be/',
	);
	
	/**
	 * Holds all the api configs
	 * @var array
	 */
	private $configs = array();
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Returns itself
	 * @return obj
	 */
	static public function getInstance()
	{
		if ( !self::$_me instanceof ShortURL )
			self::$_me = new self();
		
		return self::$_me;
	}
	
	/**
	 * Allows you to set any necessary parameter to call an api
	 * @param string $shortenerType You can use the constants from ShortURL::SHORTENER_TYPE_{API_name}
	 * @param string $param
	 * @param string $value
	 * @return obj Returns itself
	 */
	public function setParam( $shortenerType, $param, $value )
	{
		if ( !isset( $this -> apis[ $shortenerType ] ) )
			throw new Exception( "Invalid shortener type." );
		
		$this -> configs[ $shortenerType ][ $param ] = $value;
		
		return self::$_me;
	}
	
	/**
	 * Calls the api you specify  to make your url short
	 * @param string $url
	 * @param string $shortenerType You can use the constants from ShortURL::SHORTENER_TYPE_{API_name}
	 * @param string $shortenerType2 You can use the constants from ShortURL::SHORTENER_TYPE_{API_name}
	 * @return string
	 */
	public function getUrl( $url, $shortenerType, $shortenerType2 = null )
	{
		switch ( $shortenerType ) {
			case self::SHORTENER_TYPE_ADFLY :
				$url = $this -> _callAdfly( $url );
			break;
			
			case self::SHORTENER_TYPE_TINYURL :
				$url = $this -> _callTinyUrl( $url );
			break;
			
			case self::SHORTENER_TYPE_GOOGLE :
				$url = $this -> _callGoogle( $url );
			break;
			
			case self::SHORTENER_TYPE_YOUTUBE :
				$url = $this -> _callYoutube( $url );
			break;
			
			default:
				throw new Exception( "Invalid shortener type." );
			break;
		}
		
		if ( $shortenerType2 )
			return $this -> getUrl( $url, $shortenerType2 );
		
		return $url;
	}
	
	/**
	 * Uses adf.ly api to shorten the url
	 * @param string $url
	 * @return string
	 */
	private function _callAdfly( $url )
	{
		//Getting all the configs for adfly
		$params = array();
		if ( isset( $this -> configs[ self::SHORTENER_TYPE_ADFLY ] ) )
			$params = $this -> configs[ self::SHORTENER_TYPE_ADFLY ];
		
		$params['url'] = $url;
		
		//Setting default params
		if ( !isset( $params['advert_type'] ) )
			$params['advert_type'] 	= 'int';
		if ( !isset( $params['domain'] ) )
			$params['domain'] 	= 'adf.ly';
		
		if ( !isset( $params['uid'] ) || !isset( $params['key'] ) )
			throw new Exception( 'Adf.ly "key" or "uid" are not setted.' );
		
		$this -> setMethod( 'GET' );
		$this -> setUrl( $this -> apis[ self::SHORTENER_TYPE_ADFLY ] );
		
		return $this -> call( '', $params, false );
	}
	
	/**
	 * Uses tinyurl.com api to shorten the url
	 * @param string $url
	 * @return string
	 */
	private function _callTinyUrl( $url )
	{
		//Getting all the configs for tinyurl
		$params = array();
		if ( isset( $this -> configs[ self::SHORTENER_TYPE_YOUTUBE ] ) )
			$params = $this -> configs[ self::SHORTENER_TYPE_TINYURL ];
		
		$params['url'] = $url;
		
		$this -> setMethod( 'GET' );
		$this -> setUrl( $this -> apis[ self::SHORTENER_TYPE_TINYURL ] );
		
		return $this -> call( '', $params, false );
	}
	
	/**
	 * Uses y2u.be api to shorten the url
	 * @param string $url
	 * @return string
	 */
	private function _callYoutube( $url )
	{
		$info = parse_url( $url );
		
		if ( $info['host'] != 'www.youtube.com' )
			throw new Exception( "The url \"$url\" isn't from youtube." );
		
		$getInfo = array();
		parse_str( $info['query'], $getInfo );
		
		if ( !isset( $getInfo['v'] ) )
			throw new Exception( "The url \"$url\" doesn't contain the video id." );
		
		return $this -> apis[ self::SHORTENER_TYPE_YOUTUBE ] . $getInfo['v'];
	}
}

