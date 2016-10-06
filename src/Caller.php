<?php

namespace MASNathan\APICaller;

use MASNathan\Parser\Parser;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Caller
 *
 * @package MASNathan\APICaller
 */
abstract class Caller
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Caller constructor.
     *
     * @param Client $client APICaller Client Instance
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->init();
    }

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
        $endpoint = rtrim($this->client->getEndpoint(), '/');
        $section = ltrim($section, '/');
        $params = http_build_query($uriParams);

        if ($params) {
            return sprintf("%s/%s?%s", $endpoint, $section, $params);
        } else {
            return sprintf("%s/%s", $endpoint, $section);
        }
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
    protected function get($section, array $params = [], $headers = [])
    {
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
    protected function head($section, array $params = [], $headers = [])
    {
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
    protected function trace($section, array $params = [], $headers = [])
    {
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
    protected function post($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
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
    protected function put($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
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
    protected function patch($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
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
    protected function delete($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
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
    protected function options($section, $body = null, array $headers = [])
    {
        if (is_array($body)) {
            $body = http_build_query($body);
        }

        return $this->client->options($this->getUrl($section), $headers, $body);
    }

    /**
     * Handles the body content of a response
     *
     * @param ResponseInterface $response    Response instance
     * @param string|null       $contentType Content Type
     *
     * @return array|string
     */
    protected function handleResponseContent(ResponseInterface $response, $contentType = null)
    {
        $contents = $response->getBody()->getContents();

        if (!$contentType) {
            $contentTypeHeaderLine = $response->getHeaderLine('Content-Type');

            if (stripos($contentTypeHeaderLine, 'application/json') !== false) {
                $contentType = 'json';
            } elseif (stripos($contentTypeHeaderLine, 'application/xml') !== false) {
                $contentType = 'xml';
            }
        }

        if ($contentType) {
            return Parser::data($contents)->from($contentType)->toArray();
        }

        return $contents;
    }
}
