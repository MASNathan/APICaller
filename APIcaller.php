<?php

/**
 * APIcaller - Helps you build API wrappers
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller github repository
 * @link http://masnathan.users.phpclasses.org/package/8116-PHP-Send-requests-to-different-Web-services-APIs.html PHP Classes
 * @version 0.2.0
 */
class APIcaller
{
	/**
	 * Supported Content types, 'none' isn't actually a content type itself but you get the idea
	 */
	const CONTENT_TYPE_NONE = 'none';
	const CONTENT_TYPE_JSON = 'json';
	const CONTENT_TYPE_XML 	= 'xml';
	
	/**
	 * Communication standards allowed
	 */
	 const APICALLER_METHOD_GET			= 'GET';
	 const APICALLER_METHOD_POST		= 'POST';
	 const APICALLER_METHOD_PUT			= 'PUT';
	 const APICALLER_METHOD_DELETE		= 'DELETE';
	
	/**
	 * Var that holds all the default params
	 * @var array
	 */
	private $_defaultParams = array();
	
	/**
	 * Url of the API to call
	 * @var string
	 */
	private $_api_url = '';
	
	/**
	 * Method to use on the call
	 * @var string
	 */
	private $_method = APIcaller::APICALLER_METHOD_GET;
	
	/**
	 * Holds the last call information
	 * @var string
	 */
	private $_lastCall = array();
	
	/**
	 * Data format that the api you are calling will return to be parsed
	 * @var string [none|json|xml] 
	 */
	private $_format = APIcaller::APICALLER_CONTENT_TYPE_JSON;
	
	private static $opts_get = array(

	);

	public function __construct()
	{
		//do stuff
	}
	
	/**
	 * Sets a default parameter
	 * @param string	$param
	 * @param string|integer|double
	 * @return obj Returns itself
	 */
	final protected function setDefault( $param, $value )
	{
		$this -> _defaultParams[ $param ] = $value;
		
		return self::$_me;
	}
	
	/**
	 * Clears all the default params
	 * @return obj Returns itself
	 */
	final protected function clearDefaults()
	{
		$this -> _defaultParams = array();
		
		return self::$_me;
	}

	/**
	 * Sets the Method to use when calling the API
	 * @param string 	$method [ GET | POST | PUT | DELETE ]
	 * @return obj Returns itself
	 * @throws Exception If method is not valid
	 */
	final protected function setMethod( $method )
	{
		if ( in_array( $method, array( 'GET', 'POST', 'PUT', 'DELETE' ) ) )
			$this -> _method = $method;
		else
			throw new Exception( "Invalid standard communication." );
		
		return self::$_me;
	}
	
	/**
	 * Sets the URL of the API
	 * @param string	$url
	 * @return obj Returns itself
	 * @throws Exception If URL is not valid
	 */
	final protected function setUrl( $url )
	{
		if ( !filter_var($url, FILTER_VALIDATE_URL) )
			throw new Exception( "Invalid URL" );
		
		$this -> _api_url = $url;
		
		return self::$_me;
	}
	
	/**
	 * Sets the Format of the retrieved data to be parsed
	 * @param string 	$format
	 * @return obj Returns itself
	 * @throws Exception If format is not valid
	 */
	final protected function setFormat( $format )
	{
		if ( !in_array( $format, array( 'none', 'json', 'xml' ) ) )
			throw new Exception( sprintf( "APIcaller doesn't support '%s' format.", $format ) );
		
		$this -> _format = $format;
		
		return self::$_me;
	}
	
	/**
	 * Calls the API and returns the data as an Array
	 * @param string	$section Name of the file or path you need to call
	 * @param array|string $params Params to use on the query or the xml/json string you want to POST
	 * @param string	$contentType Content type of the data you want to POST
	 * @return array|null
	 * @throws Exception Throws a exception if there is no URL defined
	 */
	protected function call( $section, $params, $contentType = null )
	{
		if ( !$this -> _api_url )
			throw new Exception( "You need to set a URL!" );
		
		if ( $contentType && !in_array( $contentType, array( 'json', 'xml' ) ) )
			throw new Exception( sprintf( "Content type not supported: \"%s\".", $contentType ) );
		
		if ( !$contentType )
			$params = array_merge( $params, $this -> _defaultParams );
		
		try {
			$this -> _lastCall = array( 'url' => $this ->_api_url . $section, 'params' => $params );
			
			$curl = curl_init();
			
			switch ( $this -> _method ) {
				case 'POST':
					curl_setopt( $curl, CURLOPT_URL, $this ->_api_url . $section );
					curl_setopt( $curl, CURLOPT_POST, true );
					
					if ( is_string( $params ) && !is_null( $contentType ) ) {
						/**
						 * @todo if the $params is an array, generate the json or the xml string
						 */
						curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false);
						curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt( $curl, CURLOPT_HTTPHEADER, $this -> _getContentType( $contentType ) );
						curl_setopt( $curl, CURLOPT_POSTFIELDS, $params );
					} else { //Regular POST
						curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $params ) );
					}
				break;
				
				case 'PUT':
					curl_setopt( $curl, CURLOPT_URL, $this ->_api_url . $section );
					curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );
					curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $params ) );
				break;
				
				case 'DELETE':
					curl_setopt( $curl, CURLOPT_URL, $this ->_api_url . $section );
					curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );
					curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $params ) );
				break;
				
				default: //GET
					curl_setopt( $curl, CURLOPT_URL, $this ->_api_url . $section . '?' . http_build_query( $params ) );
				break;
			}
			
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5 );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);

			$result = curl_exec( $curl );
			
			$this -> _lastCall['data']	= $result;
			
			curl_close($curl);
		} catch( Exception $e ) {
			return null;
		}
		
		switch ( $this -> _format ) {
			case 'json':
				return $this -> _parseJson( $result );
			break;
			
			case 'xml':
				return $this -> _parseXml( $result );
			break;
			
			default:
				return $result;
			break;
		}
	}

	/**
	 * Parses a json string into an array
	 * @param string	$string
	 * @return array
	 */
	private static function parseJson($str)
	{
		$data = json_decode($str, true);

		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $data;
	        case JSON_ERROR_DEPTH:
	            return array( 'error' => 'Maximum stack depth exceeded' );
	        case JSON_ERROR_STATE_MISMATCH:
	            return array( 'error' => 'Underflow or the modes mismatch' );
	        case JSON_ERROR_CTRL_CHAR:
	            return array( 'error' => 'Unexpected control character found' );
	        case JSON_ERROR_SYNTAX:
	            return array( 'error' => 'Syntax error, malformed JSON' );
	        case JSON_ERROR_UTF8:
	            return array( 'error' => 'Malformed UTF-8 characters, possibly incorrectly encoded' );
	        default:
	            return array( 'error' => 'Unknown error on JSON file' );
    	}
	}

	/**
	 * Parses a xml string into an array
	 * @param string	$string
	 * @return array
	 */
	private static function parseXml($str)
	{
		return self::parseJson(json_encode((array) simplexml_load_string($str), true));
	}

	/**
	 * Parses the data passed into the requested data type
	 * @param string	$string
	 * @param string	$data_type You can use one of the following constants APIcaller::CONTENT_TYPE_JSON, APIcaller::CONTENT_TYPE_XML, APIcaller::CONTENT_TYPE_NONE
	 * @return array
	 */
	private static function parseData($str, $data_type)
	{
		switch ($data_type) {
			case self::CONTENT_TYPE_JSON:
				return self::parseJson($str);
			break;
			
			case self::CONTENT_TYPE_XML:
				return self::parseXml($str);
			break;

			default:
				return $str;
			break;
		}
	}

	/**
	 * Returns the last call info
	 * @return array
	 */
	public function getLastCall()
	{
		return $this -> _lastCall;
	}
	
	/**
	 * Returns the content type string to add to the header
	 * @param string 	$contentType
	 * @return array
	 */
	private function _getContentType( $contentType )
	{
		switch ( $contentType ) {
			case 'xml':
				return array( 'Content-Type: text/xml' );
			break;
			
			case 'json':
				return array( 'Content-Type: application/json' );
			break;
			
			default:
				return array();
			break;
		}
	}

	public static function get()
	{
		if (func_num_args() == 0) {
			throw new InvalidArgsException("You need specify at least the URL to call");
		}

		$data      = func_get_args();
		$url       = null;
		$params    = null;
		$callback  = null;
		$data_type = 'string';

		if (!is_string($data[0]) || !filter_var($data[0], FILTER_VALIDATE_URL)) {
			throw new InvalidArgsException("The URL you specified is not valid.");
		} else {
			$url = array_shift($data);
		}

		//Is there any parameters to add?
		if (count($data) > 0 && is_array($data[0])) {
			$params = array_shift($data);
		}
		
		//Is there any callback function to call?
		if (count($data) > 0 && is_callable($data[0])) {
			$callback = array_shift($data);
		}
		
		//Is there any data type?
		if (count($data) > 0 && is_string($data[0])) {
			$data_type = array_shift($data);
		}

		if (!is_null($params)) {
			$url .= '?' . http_build_query($params);
		}
		
		$data = self::curl_it($url);

		$data = self::parseData($data, $data_type);

		if (!is_null($callback)) {
			$data = $callback($data);
		}

		return $data;
	}

	public static function post()
	{

	}

	public static function put()
	{

	}

	public static function delete()
	{

	}

	private static function curl_it($url, $opts = array())
	{
		$curl = curl_init();
		
		curl_setopt( $curl, CURLOPT_URL, $url);

		curl_setopt_array($curl, $opts);
		
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec( $curl );
		
		curl_close($curl);

		return $result;
	}
}
