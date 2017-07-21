<?php

namespace MASNathan\APICaller\Clients;

use Http\Client\Common\HttpMethodsClient as HttpMethodsClientParent;
use MASNathan\APICaller\Operation;
use Psr\Http\Message\RequestInterface;

class HttpMethodsClient extends HttpMethodsClientParent
{
    /**
     * @var Operation
     */
    protected $lastOperation;

    /**
     * Forward to the underlying HttpClient.
     *
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request)
    {
        $this->lastOperation = new Operation($request);

        $response = parent::sendRequest($request);

        $this->lastOperation->setResponse($response);

        return $response;
    }

    /**
     * @return Operation
     */
    public function getLastOperation()
    {
        return $this->lastOperation;
    }
}
