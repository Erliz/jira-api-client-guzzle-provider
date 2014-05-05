<?php

namespace Erliz\JiraApiClient\Http;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\RequestInterface;

/**
 * Client.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class Client implements ClientInterface
{
    /** @var GuzzleClient */
    private $client;
    /** @var array */
    private $headers = array();

    public function __construct()
    {
        $this->client = new GuzzleClient();
    }

    /**
     * @inheritdoc
     */
    public function get($url)
    {
        $request = $this->client->get($url);
        $this->setHeaders($request);
        $response = $request->send();

        return json_decode($response->getBody(true));
    }

    /**
     * @inheritdoc
     */
    public function post($url, $params)
    {
        $request = $this->client->post($url, null, $params);
        $this->setHeaders($request);

        $response = $request->send();

        return json_decode($response->getBody(true));
    }

    /**
     * @inheritdoc
     */
    public function put($url, $params)
    {
        $request = $this->client->put($url, null, json_encode($params));
        $this->setHeaders($request);

        $response = $request->send();

        return $response->getStatusCode() == 204;
    }

    /**
     * @inheritdoc
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    private function setHeaders(RequestInterface $request)
    {
        foreach($this->headers as $name => $value) {
            $request->setHeader($name, $value);
        }

        return $request;
    }
}
