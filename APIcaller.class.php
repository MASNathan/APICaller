<?php

class APIcaller
{
	private static $_me = null;
	
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
	private $_method = 'GET';
	
	private $_lastCall = '';
	
	public function __construct()
	{
	}
	
	/**
	 * Returns itself
	 * @return obj
	 */
	static public function getInstance()
	{
		if ( !self::$_me instanceof APIcaller )
			self::$_me = new self();
		
		return self::$_me;
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
		$availableMethods = array( 'GET', 'POST', 'PUT', 'DELETE' );
		
		if ( in_array( $method, $availableMethods ) )
			$this -> _method = $method;
		else
			throw new Exception( "Invalid standart communication." );
		
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
	 * Calls the API and returns the data as an Array
	 * @param string	$section Name of the file or path you need to call
	 * @param array		$params Params to use on the query
	 * @return array|null
	 * @throws Exception Throws a exception if there is no URL defined
	 */
	protected function call( $section, $params )
	{
		if ( !$this -> _api_url )
			throw new Exception( "You need to set a URL!" );
		
		$params = array_merge( $params, $this -> _defaultParams );
		
		try {
			$this -> _lastCall = $this ->_api_url . $section . '?' . http_build_query( $params );
			
			$curl = curl_init();
			
			switch ( $this -> _method ) {
				case 'POST':
					curl_setopt( $curl, CURLOPT_URL, $this ->_api_url . $section );
					curl_setopt( $curl, CURLOPT_POST, true );
					curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $params ) );
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

			curl_close($curl);
			//*/
		} catch( Exception $e ) {
			return null;
		}
		
		$data = json_decode( $result, true );

		switch ( json_last_error() ) {
			case JSON_ERROR_NONE:
				return $data;
				break;
	        case JSON_ERROR_DEPTH:
	            throw new Exception("Maximum stack depth exceeded");
	        	break;
	        case JSON_ERROR_STATE_MISMATCH:
	            throw new Exception("Underflow or the modes mismatch");
	        	break;
	        case JSON_ERROR_CTRL_CHAR:
	            throw new Exception("Unexpected control character found");
	        	break;
	        case JSON_ERROR_SYNTAX:
	            throw new Exception("Syntax error, malformed JSON");
	        	break;
	        case JSON_ERROR_UTF8:
	            throw new Exception("Malformed UTF-8 characters, possibly incorrectly encoded");
	        	break;
	        default:
	            throw new Exception("Unknown error on JSON file");
	        	break;
    	}
	}

	public function getLastCall()
	{
		return $this -> _lastCall;
	}
}
