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
    protected $client;

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
