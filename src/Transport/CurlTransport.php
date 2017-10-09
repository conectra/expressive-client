<?php

namespace Conectra\Expressive\Client\Transport;

use Curl\Curl;

/**
 * Class CurlTransport
 *
 * @package MsBoletos\Schemas\Titulo\Client
 */
class CurlTransport
{

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var ApiUri
     */
    protected $apiUri;

    /**
     * CurlTransport constructor.
     *
     * @param ApiUri $apiUri
     */
    public function __construct(ApiUri $apiUri)
    {
        $this->apiUri = $apiUri;
        $this->curl   = new Curl();
    }

    /**
     * @param array $data
     * @param       $endpoint
     *
     * @return array
     */
    public function get(array $data = [], $endpoint)
    {
        $response = $this->curl->get($this->apiUri->get($endpoint), $data);

        return $this->parseResponse($response);
    }

    /**
     * @param array $data
     * @param       $endpoint
     *
     * @return array
     */
    public function post(array $data = [], $endpoint)
    {
        $response = $this->curl->post($this->apiUri->get($endpoint), $data);

        return $this->parseResponse($response);
    }

    /**
     * @param Curl $curl
     *
     * @return array
     */
    protected function parseResponse(Curl $curl): array
    {
        $this->checkForCurlError($curl);

        return $this->decodeCurlResponse($curl);
    }

    /**
     * @param Curl $curl
     *
     * @throws \Exception
     */
    protected function checkForCurlError(Curl $curl)
    {
        if ($curl->error) {
            throw new \Exception($curl->error_code . ' - ' . $curl->error_message);
        }
    }

    /**
     * @param Curl $curl
     *
     * @return mixed
     * @throws \Exception
     */
    protected function decodeCurlResponse(Curl $curl): array
    {
        $response = json_decode($curl->response, true);

        if (!$response) {
            throw new \Exception('error decoding api response');
        }

        return $response;
    }
}