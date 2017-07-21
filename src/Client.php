<?php

namespace MASNathan\APICaller;

use MASNathan\APICaller\Clients\HttpMethodsClient;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Psr\Http\Message\ResponseInterface;

/**
 * Class APICaller
 *
 * @package MASNathan\APICaller
 */
abstract class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var PluginClient
     */
    protected $pluginClient;

    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var HttpMethodsClient
     */
    private $client;

    protected $defaultParameters = [];

    protected $parameters = [];

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->setDefaultHeaders([
            'User-Agent' => 'PHP APICaller SDK',
        ]);
    }

    /**
     * Returns the API Endpoint
     *
     * @return string
     */
    abstract public function getEndpoint();

    /**
     * Returns the formated URL to the requested section
     *
     * @param string $section   API Section
     * @param array  $uriParams Params
     *
     * @return string
     */
    protected function getUrl($section, array $uriParams = [])
    {
        $endpoint = rtrim($this->getEndpoint(), '/');
        $section = ltrim($section, '/');
        $params = http_build_query($uriParams);

        if ($params) {
            return sprintf("%s/%s?%s", $endpoint, $section, $params);
        } else {
            return sprintf("%s/%s", $endpoint, $section);
        }
    }

    /**
     * @param HttpClient $httpClient HttpClient implementation
     *
     * @return Client
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param MessageFactory $messageFactory MessageFactory implementation
     *
     * @return Client
     */
    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;

        return $this;
    }

    /**
     * Sets the Default Headers
     *
     * @param array $defaultHeaders Default Headers
     *
     * @return Client
     */
    protected function setDefaultHeaders(array $defaultHeaders)
    {
        $this->plugins['default_headers'] = new HeaderDefaultsPlugin($defaultHeaders);

        return $this;
    }

    /**
     * Sets the Default Parameters
     *
     * @param array $defaultParameters Default Parameters
     *
     * @return Client
     */
    protected function setDefaultParameters(array $defaultParameters)
    {
        $this->defaultParameters = $defaultParameters;

        return $this;
    }

    /**
     * Sets the Mandatory Headers
     *
     * @param array $headers Headers
     *
     * @return Client
     */
    protected function setHeaders(array $headers)
    {
        $this->plugins['headers'] = new HeaderSetPlugin($headers);

        return $this;
    }

    /**
     * Sets the Mandatory Parameters
     *
     * @param array $parameters Parameters
     *
     * @return Client
     */
    protected function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Initialises the HttpClient
     *
     * @return void
     */
    public function init()
    {
        $this->pluginClient = new PluginClient(
            $this->httpClient ?: HttpClientDiscovery::find(),
            $this->plugins
        );

        $this->client = new HttpMethodsClient(
            $this->pluginClient,
            $this->messageFactory ?: MessageFactoryDiscovery::find()
        );
    }

    /**
     * Sends a GET request.
     *
     * @param string $section URI section
     * @param array  $params  Http get parameters
     * @param array  $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function get($section, array $params = [], $headers = [])
    {
        $params = array_merge($this->parameters, $params, $this->defaultParameters);

        return $this->client->get($this->getUrl($section, $params), $headers);
    }

    /**
     * Sends a HEAD request.
     *
     * @param string $section URI section
     * @param array  $params  Http head parameters
     * @param array  $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function head($section, array $params = [], $headers = [])
    {
        $params = array_merge($this->parameters, $params, $this->defaultParameters);

        return $this->client->head($this->getUrl($section, $params), $headers);
    }

    /**
     * Sends a TRACE request.
     *
     * @param string $section URI section
     * @param array  $params  Http trace parameters
     * @param array  $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function trace($section, array $params = [], $headers = [])
    {
        $params = array_merge($this->parameters, $params, $this->defaultParameters);

        return $this->client->trace($this->getUrl($section, $params), $headers);
    }

    /**
     * Sends a POST request.
     *
     * @param string                            $section URI section
     * @param string|array|StreamInterface|null $body    Body content or Http post parameters
     * @param array                             $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function post($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
            $body = array_merge($this->parameters, $body, $this->defaultParameters);

            $body = http_build_query($body);
        }

        return $this->client->post($this->getUrl($section), $headers, $body);
    }

    /**
     * Sends a PUT request.
     *
     * @param string                            $section URI section
     * @param string|array|StreamInterface|null $body    Body content or Http put parameters
     * @param array                             $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function put($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
            $body = array_merge($this->parameters, $body, $this->defaultParameters);

            $body = http_build_query($body);
        }

        return $this->client->put($this->getUrl($section), $headers, $body);
    }

    /**
     * Sends a PATCH request.
     *
     * @param string                            $section URI section
     * @param string|array|StreamInterface|null $body    Body content or Http patch parameters
     * @param array                             $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function patch($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
            $body = array_merge($this->parameters, $body, $this->defaultParameters);

            $body = http_build_query($body);
        }

        return $this->client->patch($this->getUrl($section), $headers, $body);
    }

    /**
     * Sends a DELETE request.
     *
     * @param string                            $section URI section
     * @param string|array|StreamInterface|null $body    Body content or Http delete parameters
     * @param array                             $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function delete($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
            $body = array_merge($this->parameters, $body, $this->defaultParameters);

            $body = http_build_query($body);
        }

        return $this->client->delete($this->getUrl($section), $headers, $body);
    }

    /**
     * Sends a OPTIONS request.
     *
     * @param string                            $section URI section
     * @param string|array|StreamInterface|null $body    Body content or Http options parameters
     * @param array                             $headers Http headers
     *
     * @throws Exception
     *
     * @return ResponseInterface PSR-7 Response
     */
    public function options($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
            $body = array_merge($this->parameters, $body, $this->defaultParameters);

            $body = http_build_query($body);
        }

        return $this->client->options($this->getUrl($section), $headers, $body);
    }

    /**
     * @return Operation
     */
    public function getLastOperation()
    {
        return $this->client->getLastOperation();
    }
}
