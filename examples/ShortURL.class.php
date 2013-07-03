<?php

require_once '../APIcaller.class.php';

/**
 * ShortURL - Uses either adlfy, tinyurl or y2u.be to shorten an url
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller/blob/master/examples/ShortURL.class.php github repository
 * @version 0.1.0 - 29-06-2013 20:00:23
 * 		- Release into the wild
 * 			0.1.1 - 03-07-2013 16:14:35
 * 		- Added google shortener
 */
class ShortURL extends APIcaller
{
	private static $_me = null;
	
	const SHORTENER_TYPE_ADFLY 		= 'adlfy';
	const SHORTENER_TYPE_TINYURL 	= 'tinyurl';
	const SHORTENER_TYPE_GOOGLE 	= 'google';
	const SHORTENER_TYPE_YOUTUBE 	= 'youtube';
	
	/**
	 * Holds api urls
	 * @var array
	 */
	private $apis = array(
		'adlfy'		=> 'http://api.adf.ly/api.php',
		'tinyurl'	=> 'http://tinyurl.com/api-create.php',
		'google'	=> 'https://www.googleapis.com/urlshortener/v1/url',
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
		
		$this -> setUrl( $this -> apis[ self::SHORTENER_TYPE_ADFLY ] );
		$this -> setMethod( APIcaller::APICALLER_METHOD_GET );
		$this -> setFormat( APIcaller::APICALLER_CONTENT_TYPE_NONE );
		
		return $this -> call( '', $params );
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
		
		$this -> setUrl( $this -> apis[ self::SHORTENER_TYPE_TINYURL ] );
		$this -> setMethod( APIcaller::APICALLER_METHOD_GET );
		$this -> setFormat( APIcaller::APICALLER_CONTENT_TYPE_NONE );
		
		return $this -> call( '', $params );
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

	/**
	 * Uses google api to shorten the url
	 * @param string $url
	 * @return string
	 */
	private function _callGoogle( $url )
	{
		$this -> setUrl( $this -> apis[ self::SHORTENER_TYPE_GOOGLE ] );
		$this -> setMethod( APIcaller::APICALLER_METHOD_POST );
		$this -> setFormat( APIcaller::APICALLER_CONTENT_TYPE_JSON );
		
		$params = array( 'longUrl' => $url );

		$section = '';
		if ( isset( $this -> configs[ self::SHORTENER_TYPE_GOOGLE ] ) )
			$section = '?' . http_build_query( $this -> configs[ self::SHORTENER_TYPE_GOOGLE ] );
		
		$data = $this -> call( $section, json_encode( $params ), APIcaller::APICALLER_CONTENT_TYPE_JSON );
		
		return $data['id'];
	}
}

