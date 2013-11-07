<?php

//Loading the exceptions
require_once __DIR__ . "/exceptions/InvalidArgsException.php";
require_once __DIR__ . "/exceptions/InvalidContentTypeException.php";
require_once __DIR__ . "/exceptions/InvalidMethodException.php";
require_once __DIR__ . "/exceptions/InvalidUrlException.php";

/**
 * APIcaller - Helps you build API wrappers
 * 
 * @author 	André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller github repository
 * @link http://masnathan.users.phpclasses.org/package/8116-PHP-Send-requests-to-different-Web-services-APIs.html PHP Classes
 * @version 0.2.1
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
	 const METHOD_GET    = 'GET';
	 const METHOD_POST   = 'POST';
	 const METHOD_PUT    = 'PUT';
	 const METHOD_DELETE = 'DELETE';
	
	/**
	 * Url of the API to call
	 * @var string
	 */
	private $api_url = null;

	/**
	 * Var that holds all the default params
	 * @var array
	 */
	private $default_params = array();
	
	/**
	 * Method to use on the call
	 * @var string
	 */
	private $default_method = APIcaller::METHOD_GET;
	
	/**
	 * Data format that the api you are calling will return to be parsed
	 * @var string [none|json|xml] 
	 */
	private $default_format = APIcaller::CONTENT_TYPE_NONE;
	
	/**
	 * Holds the last call information
	 * @var string
	 */
	private $last_call = array();
	
	/**
	 * CURL default options for GET calls
	 * @var array
	 */
	private static $opts_get    = array();

	/**
	 * CURL default options for POST calls
	 * @var array
	 */
	private static $opts_post   = array(
		CURLOPT_POST          => true,
	);

	/**
	 * CURL default options for PUT calls
	 * @var array
	 */
	private static $opts_put    = array(
		CURLOPT_CUSTOMREQUEST => self::METHOD_PUT,
	);

	/**
	 * CURL default options for DELETE calls
	 * @var array
	 */
	private static $opts_delete = array(
		CURLOPT_CUSTOMREQUEST => self::METHOD_DELETE,
	);

	/**
	 * Allows you to set the default URL, call method and the parse format if any
	 * @param string $url The API URL
	 * @param string $method The Request Standard you want to use [GET|POST|PUT|DELETE]
	 * @param string $format The format of the data the webservice will return [none|json|xml]
	 */
	public function __construct($url = null, $method = null, $format = null)
	{
		if (!is_null($url)) {
			$this->setUrl($url);
		}
		if (!is_null($method)) {
			$this->setMethod($method);
		}
		if (!is_null($format)) {
			$this->setFormat($format);
		}
	}
	
	/**
	 * Sets a default parameter
	 * @param string	$param
	 * @param string|integer|double $value
	 * @return obj Returns itself
	 */
	final protected function setDefault($param, $value)
	{
		$this->default_params[$param] = $value;
		
		return $this;
	}
	
	/**
	 * Clears all the default params
	 * @return obj Returns itself
	 */
	final protected function clearDefaults()
	{
		$this->default_params = array();
		
		return $this;
	}

	/**
	 * Sets the Method to use when calling the API
	 * @param string 	$method [ GET | POST | PUT | DELETE ]
	 * @return obj Returns itself
	 * @throws InvalidMethodException If method is not valid
	 */
	final protected function setMethod($method)
	{
		if (in_array($method, array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE))) {
			$this->default_method = $method;
		} else {
			throw new InvalidMethodException;
		}

		return $this;
	}
	
	/**
	 * Sets the URL of the API
	 * @param string	$url
	 * @return obj Returns itself
	 * @throws InvalidUrlException If URL is not valid
	 */
	final protected function setUrl($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new InvalidUrlException;
		} else {
			$this->api_url = $url;
		}
		
		return $this;
	}
	
	/**
	 * Sets the Format of the retrieved data to be parsed
	 * @param string 	$format Can be one of the following APIcaller::CONTENT_TYPE_NONE, APIcaller::CONTENT_TYPE_JSON or APIcaller::CONTENT_TYPE_XML
	 * @return obj Returns itself
	 * @throws InvalidContentTypeException If format is not valid
	 */
	final protected function setFormat($format)
	{
		if (!in_array($format, array(self::CONTENT_TYPE_NONE, self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_XML))) {
			throw new InvalidContentTypeException(sprintf( "APIcaller doesn't support '%s' format.", $format));
		} else {
			$this->default_format = $format;	
		}

		return $this;
	}
	
	/**
	 * Calls the API and returns the data as an Array
	 * @param string	$section Name of the file or path you need to call
	 * @param array|string $params Params to use on the query or the xml/json string you want to POST
	 * @param string	$content_type Content type of the data you want to POST
	 * @return array|null
	 * @throws InvalidUrlException Throws a exception if there is no URL defined
	 * @throws InvalidContentTypeException Throws a exception if the content type is not supported
	 */
	protected function call( $section, $params, $content_type = null )
	{
		if (!$this->api_url) {
			throw new InvalidUrlException("You need to set a URL!");
		}

		if ($content_type && !in_array($content_type, array(self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_XML))) {
			throw new InvalidContentTypeException(sprintf("Content type not supported: \"%s\".", $content_type));
		}

		if (!$content_type) {
			$params = array_merge($params, $this->default_params);
		}
		
		try {
			$this->last_call = array(
				'url'    => $this->api_url . $section,
				'params' => $params,
			);
			
			switch ($this->default_method) {
				case self::METHOD_GET:
					$data = self::get($this->api_url . $section, $params);
					break;

				case self::METHOD_POST:
					switch ($content_type) {
						case self::CONTENT_TYPE_JSON:
							$data = self::post_json($this->api_url . $section, $params);
							break;
						
						case self::CONTENT_TYPE_XML:
							$data = self::post_xml($this->api_url . $section, $params);
							break;
						
						default: //Regular POST
							$data = self::post($this->api_url . $section, $params);
							break;
					}
					break;
				
				case self::METHOD_PUT:
					$data = self::put($this->api_url . $section, $params);
					break;
				
				case self::METHOD_DELETE:
					$data = self::delete($this->api_url . $section, $params);
					break;
				
				default:
					throw new InvalidMethodException;
					break;
			}
			
			$this->last_call['data'] = $data;
		} catch(Exception $e) {
			return null;
		}
		
		return self::parseData($data, $this->default_format);
	}

	/**
	 * Returns the last call info
	 * @return array
	 */
	public function getLastCall()
	{
		return $this->last_call;
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
	            return array('error' => 'Maximum stack depth exceeded');
	        case JSON_ERROR_STATE_MISMATCH:
	            return array('error' => 'Underflow or the modes mismatch');
	        case JSON_ERROR_CTRL_CHAR:
	            return array('error' => 'Unexpected control character found');
	        case JSON_ERROR_SYNTAX:
	            return array('error' => 'Syntax error, malformed JSON');
	        case JSON_ERROR_UTF8:
	            return array('error' => 'Malformed UTF-8 characters, possibly incorrectly encoded');
	        default:
	            return array('error' => 'Unknown error on JSON file');
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
	 * Calls a URL using the GET method
	 * 
	 * APIcaller::get( string $url [, array $params [, function $callback [, string $data_type]]]);
	 * Here are a few examples:
	 * 		- APIcaller::get('http://path_to_api.com');
	 * 		- APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'));
	 * 		- APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); });
	 * 		- APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); }, 'json');
	 * 		- APIcaller::get('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), 'json');
	 * 		- APIcaller::get('http://path_to_api.com', 'json');
	 * 		- APIcaller::get('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
	 * Any of the above examples is aceptable
	 * @return string|array|null
	 */
	final public static function get()
	{
		return self::caller(func_get_args(), self::METHOD_GET);
	}

	/**
	 * Calls a URL using the POST method
	 * 
	 * APIcaller::post( string $url [, array $params [, function $callback [, string $data_type]]]);
	 * Here are a few examples:
	 * 		- APIcaller::post('http://path_to_api.com');
	 * 		- APIcaller::post('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'));
	 * 		- APIcaller::post('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); });
	 * 		- APIcaller::post('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); }, 'json');
	 * 		- APIcaller::post('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), 'json');
	 * 		- APIcaller::post('http://path_to_api.com', 'json');
	 * 		- APIcaller::post('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
	 * Any of the above examples is aceptable
	 * @return string|array|null
	 */
	final public static function post()
	{
		return self::caller(func_get_args(), self::METHOD_POST);
	}

	/**
	 * Uses POST method and send json data to the API 
	 * 
	 * APIcaller::post_json( string $url [, string $params [, function $callback [, string $data_type]]]);
	 * Here are a few examples:
	 * 		- APIcaller::post_json('http://path_to_api.com');
	 * 		- APIcaller::post_json('http://path_to_api.com', '{"param1": "some value", "param2": "some other value"}');
	 * 		- APIcaller::post_json('http://path_to_api.com', '{"param1": "some value", "param2": "some other value"}', function(data) { var_dump($data); });
	 * 		- APIcaller::post_json('http://path_to_api.com', '{"param1": "some value", "param2": "some other value"}', function(data) { var_dump($data); }, 'json');
	 * 		- APIcaller::post_json('http://path_to_api.com', '{"param1": "some value", "param2": "some other value"}', 'json');
	 * 		- APIcaller::post_json('http://path_to_api.com', 'json');
	 * 		- APIcaller::post_json('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
	 * Any of the above examples is aceptable
	 * @return string|array|null
	 */
	final public static function post_json()
	{
		return self::caller(func_get_args(), self::METHOD_POST, self::CONTENT_TYPE_JSON);
	}

	/**
	 * Uses POST method and send xml data to the API 
	 * 
	 * APIcaller::post_xml( string $url [, string $params [, function $callback [, string $data_type]]]);
	 * Here are a few examples:
	 * 		- APIcaller::post_xml('http://path_to_api.com');
	 * 		- APIcaller::post_xml('http://path_to_api.com', '<?xml version="1.0" encoding="ISO-8859-1"?><data><param1>some value</param1><param2>some other value</param2></data>');
	 * 		- APIcaller::post_xml('http://path_to_api.com', '<?xml version="1.0" encoding="ISO-8859-1"?><data><param1>some value</param1><param2>some other value</param2></data>', function(data) { var_dump($data); });
	 * 		- APIcaller::post_xml('http://path_to_api.com', '<?xml version="1.0" encoding="ISO-8859-1"?><data><param1>some value</param1><param2>some other value</param2></data>', function(data) { var_dump($data); }, 'json');
	 * 		- APIcaller::post_xml('http://path_to_api.com', '<?xml version="1.0" encoding="ISO-8859-1"?><data><param1>some value</param1><param2>some other value</param2></data>', 'json');
	 * 		- APIcaller::post_xml('http://path_to_api.com', 'json');
	 * 		- APIcaller::post_xml('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
	 * Any of the above examples is aceptable
	 * @return string|array|null
	 */
	final public static function post_xml()
	{
		return self::caller(func_get_args(), self::METHOD_POST, self::CONTENT_TYPE_XML);
	}

	/**
	 * Calls a URL using the PUT method
	 * 
	 * APIcaller::put( string $url [, array $params [, function $callback [, string $data_type]]]);
	 * Here are a few examples:
	 * 		- APIcaller::put('http://path_to_api.com');
	 * 		- APIcaller::put('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'));
	 * 		- APIcaller::put('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); });
	 * 		- APIcaller::put('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); }, 'json');
	 * 		- APIcaller::put('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), 'json');
	 * 		- APIcaller::put('http://path_to_api.com', 'json');
	 * 		- APIcaller::put('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
	 * Any of the above examples is aceptable
	 * @return string|array|null
	 */
	final public static function put()
	{
		return self::caller(func_get_args(), self::METHOD_PUT);
	}

	/**
	 * Calls a URL using the DELETE method
	 * 
	 * APIcaller::delete( string $url [, array $params [, function $callback [, string $data_type]]]);
	 * Here are a few examples:
	 * 		- APIcaller::delete('http://path_to_api.com');
	 * 		- APIcaller::delete('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'));
	 * 		- APIcaller::delete('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); });
	 * 		- APIcaller::delete('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), function(data) { var_dump($data); }, 'json');
	 * 		- APIcaller::delete('http://path_to_api.com', array('param1' => 'some value', 'param2' => 'some other value'), 'json');
	 * 		- APIcaller::delete('http://path_to_api.com', 'json');
	 * 		- APIcaller::delete('http://path_to_api.com', function(data) { var_dump($data); }, 'json');
	 * Any of the above examples is aceptable
	 * @return string|array|null
	 */
	final public static function delete()
	{
		return self::caller(func_get_args(), self::METHOD_DELETE);		
	}

	/**
	 * Deals with the arguments "detection" and sets the rigth configs for the method you specify
	 * @param array $args
	 * @param string $method You can use the following constants APIcaller::METHOD_GET, APIcaller::METHOD_POST, APIcaller::METHOD_PUT and APIcaller::METHOD_DELETE 
	 * @return string|array Depends on the data type you use
	 */
	final public static function caller($args, $method, $content_type = null)
	{
		if (count($args) == 0) {
			throw new InvalidArgsException("You need specify at least the URL to call");
		}

		$url       = null;
		$params    = null;
		$callback  = null;
		$data_type = self::CONTENT_TYPE_NONE;
		
		if (!is_string($args[0]) || !filter_var($args[0], FILTER_VALIDATE_URL)) {
			throw new InvalidArgsException("The URL you specified is not valid.");
		} else {
			$url = array_shift($args);
		}

		//Is there any parameters to add?
		if (count($args) > 0 && is_array($args[0])) {
			$params = array_shift($args);
		}
		
		//Is there any callback function to call?
		if (count($args) > 0 && is_callable($args[0])) {
			$callback = array_shift($args);
		}
		
		//Is there any data type?
		if (count($args) > 0 && is_string($args[0])) {
			$data_type = array_shift($args);
		}
		//END of arguments treatment

		switch ($method) {
			case self::METHOD_POST:

				switch ($content_type) {
					case self::CONTENT_TYPE_JSON:
						$opts = array(
							CURLOPT_POST           => true,
							CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
							CURLOPT_POSTFIELDS     => json_encode($params),
						);
						break;
					
					case self::CONTENT_TYPE_XML:
						$opts = array(
							CURLOPT_POST           => true,
							CURLOPT_HTTPHEADER     => array('Content-Type: text/xml'),
							CURLOPT_POSTFIELDS     => $params, //@todo xml_encode function
						);
						break;
					
					default: //Regular POST
						$opts = self::$opts_post;
						if (!is_null($params)) {
							$opts[CURLOPT_POSTFIELDS] = http_build_query($params);	
						}
						break;
				}
				break;

			case self::METHOD_PUT:
				$opts = self::$opts_put;
				if (!is_null($params)) {
					$opts[CURLOPT_POSTFIELDS] = http_build_query($params);	
				}
				break;
			
			case self::METHOD_DELETE:
				$opts = self::$opts_delete;
				if (!is_null($params)) {
					$opts[CURLOPT_POSTFIELDS] = http_build_query($params);	
				}
				break;

			default: //self::METHOD_GET
				$opts = array();
				if (!is_null($params)) {
					$url .= '?' . http_build_query($params);
				}
				break;
		}

		$data = self::curl_it($url, $opts);

		$data = self::parseData($data, $data_type);

		if (!is_null($callback)) {
			$data = $callback($data);
		}

		return $data;
	} 

	/**
	 * Handles the calls using curl
	 * @param string $url The URL you want to call
	 * @param array $opts CURL options to use on the call
	 * @return string
	 */
	private static function curl_it($url, $opts = array())
	{
		$curl = curl_init();

		curl_setopt( $curl, CURLOPT_URL, $url);

		curl_setopt_array($curl, $opts);
		
		curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}
}
