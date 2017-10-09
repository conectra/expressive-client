<?php

namespace Conectra\Expressive\Client\Transport;

/**
 * Class ApiUri
 *
 * @package Conectra\Expressive\Client\Transport
 */
class ApiUri
{

    /**
     * @var array
     */
    private $endpoints = [];

    /**
     * ApiUri constructor.
     *
     * @param array $endpoints
     */
    public function __construct($endpoints = [])
    {
        $this->setEndpoints($endpoints);
    }

    /**
     * @param array $endpoints
     */
    public function setEndpoints(array $endpoints = [])
    {
        $this->endpoints = $endpoints;
    }

    /**
     * @param string $endpoint
     * @param string $uri
     */
    public function set(string $endpoint, string $uri)
    {
        $this->endpoints[$endpoint] = $uri;
    }

    /**
     * @param $endpoint
     *
     * @return mixed|null
     */
    public function get($endpoint)
    {
        return $this->endpoints[$endpoint] ?? null;
    }
}