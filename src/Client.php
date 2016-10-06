<?php

namespace MASNathan\APICaller;

use Http\Client\Common\HttpMethodsClient;
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
    public function setDefaultHeaders(array $defaultHeaders)
    {
        $this->plugins['default_headers'] = new HeaderDefaultsPlugin($defaultHeaders);

        return $this;
    }

    /**
     * Sets the Mandatory Headers
     *
     * @param array $headers Headers
     *
     * @return Client
     */
    public function setHeaders(array $headers)
    {
        $this->plugins['headers'] = new HeaderSetPlugin($headers);

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
     * @param string|UriInterface $uri
     * @param array               $headers
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function get($uri, array $headers = [])
    {
        return $this->client->get($uri, $headers);
    }

    /**
     * Sends an HEAD request.
     *
     * @param string|UriInterface $uri
     * @param array               $headers
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function head($uri, array $headers = [])
    {
        return $this->client->head($uri, $headers);
    }

    /**
     * Sends a TRACE request.
     *
     * @param string|UriInterface $uri
     * @param array               $headers
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function trace($uri, array $headers = [])
    {
        return $this->client->trace($uri, $headers);
    }

    /**
     * Sends a POST request.
     *
     * @param string|UriInterface         $uri
     * @param array                       $headers
     * @param string|StreamInterface|null $body
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function post($uri, array $headers = [], $body = null)
    {
        return $this->client->post($uri, $headers, $body);
    }

    /**
     * Sends a PUT request.
     *
     * @param string|UriInterface         $uri
     * @param array                       $headers
     * @param string|StreamInterface|null $body
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function put($uri, array $headers = [], $body = null)
    {
        return $this->client->put($uri, $headers, $body);
    }

    /**
     * Sends a PATCH request.
     *
     * @param string|UriInterface         $uri
     * @param array                       $headers
     * @param string|StreamInterface|null $body
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function patch($uri, array $headers = [], $body = null)
    {
        return $this->client->patch($uri, $headers, $body);
    }

    /**
     * Sends a DELETE request.
     *
     * @param string|UriInterface         $uri
     * @param array                       $headers
     * @param string|StreamInterface|null $body
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function delete($uri, array $headers = [], $body = null)
    {
        return $this->client->delete($uri, $headers, $body);
    }

    /**
     * Sends an OPTIONS request.
     *
     * @param string|UriInterface         $uri
     * @param array                       $headers
     * @param string|StreamInterface|null $body
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function options($uri, array $headers = [], $body = null)
    {
        return $this->client->options($uri, $headers, $body);
    }
}
