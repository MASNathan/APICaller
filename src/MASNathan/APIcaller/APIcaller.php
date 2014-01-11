<?php

namespace MASNathan\APIcaller;
use MASNathan\Curl;

/**
 * APIcaller - Helps you build API wrappers
 * 
 * @package MASNathan
 * @subpackage APIcaller
 * @author André Filipe <andre.r.flip@gmail.com>
 * @link https://github.com/ReiDuKuduro/APIcaller GitHub repo
 * @license MIT
 * @version 0.3.0
 */
class APIcaller
{	
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
	private $api_url;

	/**
	 * Var that holds all the default params
	 * @var array
	 */
	private $default_params = array();
	
	/**
	 * Method to use on the call
	 * @var string
	 */
	private $default_request_method;
	
	/**
	 * Data format that the api you are calling will return to be parsed
	 * @var string [none|json|xml] 
	 */
	private $default_response_format;
	
	/**
	 * Holds the last call information
	 * @var string
	 */
	private $last_call = array();

	/**
	 * Allows you to set the default URL, call method and the parse format if any
	 * @param string $url The API URL
	 * @param string $method The Request Standard you want to use [GET|POST|PUT|DELETE]
	 * @param string $format The format of the data the webservice will return [none|json|xml]
	 */
	public function __construct($api_url = null, $default_request_method = 'GET', $response_format = 'none')
	{
		$this->setApiUrl($api_url);
		$this->setRequestMethod($default_request_method);
		$this->setResponseFormat($response_format);
	}
	
	/**
	 * Sets the URL of the API
	 * @param string	$url
	 * @return APIcaller
	 * @throws Exception\InvalidUrlException If URL is not valid
	 */
	final protected function setApiUrl($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new Exception\InvalidUrlException;
		}
		
		$this->api_url = $url;
		return $this;
	}

	/**
	 * Sets a default parameter
	 * @param string $param
	 * @param string|integer|double $value
	 * @return APIcaller
	 */
	protected function setDefaultParameter($param, $value)
	{
		$this->default_params[$param] = $value;
		return $this;
	}

	/**
	 * Sets the Method to use when calling the API
	 * @param string $method [ GET | POST | PUT | DELETE ]
	 * @return APIcaller
	 * @throws Exception\InvalidMethodException If method is not valid
	 */
	final protected function setRequestMethod($method)
	{
		if (!in_array($method, array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE))) {
			throw new Exception\InvalidMethodException;
		}
		
		$this->default_request_method = $method;
		return $this;
	}

	/**
	 * Sets the Format of the retrieved data to be parsed
	 * @param string $format
	 * @return APIcaller
	 */
	final protected function setResponseFormat($format)
	{
		$this->default_response_format = $format;	
		return $this;
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
	 * Calls the API and returns the data as an Array
	 * @param string	$section Name of the file or path you need to call
	 * @param array|string $params Params to use on the query or the xml/json string you want to POST
	 * @param string	$content_type Content type of the data you want to POST
	 * @return array|null
	 * @throws InvalidUrlException Throws a exception if there is no URL defined
	 * @throws InvalidContentTypeException Throws a exception if the content type is not supported
	 */
	protected function call($section, $params, $content_type = null)
	{
		if (!$this->api_url) {
			throw new Exception\InvalidUrlException("You need to set a URL!");
		}

		if ($content_type && !in_array($content_type, array('json', 'xml'))) {
			throw new Exception\InvalidContentTypeException(sprintf("Content type not supported: \"%s\".", $content_type));
		}

		if (!$content_type) {
			$params = array_merge($params, $this->default_params);
		}

		$this->last_call = array();
		$this->last_call['url']      = $this->api_url . $section;
		$this->last_call['params']   = $params;
		$this->last_call['response'] = null;
		$this->last_call['data']     = null;

		try {
			$args = array(
					$this->api_url . $section, 	//URL
					$params, 					//Parameters
				);

			$response = Curl\Ch::call($this->default_request_method, $args, $content_type);
			$this->last_call['response'] = $response;

			$data = Curl\StringParser::parse($response, $this->default_response_format);
			$this->last_call['data'] = $data;

			return $data;
		} catch(\Exception $e) {
			return null;
		}
	}
}
