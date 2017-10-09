<?php

namespace Conectra\Expressive\Client\QueryBuilder;

use Conectra\Expressive\Client\Transport\CurlTransport;
use Solis\Expressive\Schema\Contracts\Entries\Property\PropertyContract;

/**
 * Class UpdateBuilder
 *
 * @package MsBoletos\Schemas\Titulo\Client
 */
class UpdateBuilder
{

    /**
     * @var CurlTransport
     */
    private $transport;

    /**
     * UpdateBuilder constructor.
     *
     * @param CurlTransport $transport
     */
    public function __construct(CurlTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param $model
     *
     * @return boolean
     */
    public function update($model)
    {
        $this->checkModelForUpdate($model);

        $response = $this->transport->post($this->getUpdateFields($model), 'update');

        return $this->handleApiResponse($response);
    }

    /**
     * @param $model
     *
     * @throws \Exception
     */
    private function checkModelForUpdate($model)
    {
        $keys = $model->getSchema()->getKeys();
        foreach ($keys as $property) {
            /**
             * @var PropertyContract $property
             */
            $value = $model->{$property->getProperty()};

            if (is_null($value)) {
                throw new \Exception('Property used as primary key cannot be null for update record');
            }
        }
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    private function getUpdateFields($model)
    {
        return $model->toArray(true);
    }

    /**
     * @param array $response
     *
     * @return boolean
     * @throws \Exception
     */
    private function handleApiResponse(array $response)
    {
        $this->hasResponseStatusEntry($response);
        $this->hasResponseDataEntry($response);
        $this->hasReponseSuccessEntry($response);

        return $this->getResponseStatus($response);
    }

    /**
     * @param array $response
     *
     * @return boolean
     * @throws \Exception
     */
    private function hasResponseStatusEntry(array $response)
    {
        $status = $response['status'] ?? null;

        if (is_null($status)) {
            $message = $response['message'] ?? 'Api invalid response';
            throw new \Exception($message);
        }

        return true;
    }

    /**
     * @param array $response
     *
     * @return boolean
     * @throws \Exception
     */
    private function hasResponseDataEntry(array $response)
    {
        $data = $response['data'] ?: false;
        if (is_null($data)) {
            throw new \Exception('Invalid api data result');
        }

        return true;
    }

    /**
     * @param array $response
     *
     * @return boolean
     * @throws \Exception
     */
    private function hasReponseSuccessEntry(array $response)
    {
        $data = $response['data'][0];

        $success = $data['success'] ?? null;

        if (is_null($success)) {
            throw new \Exception('Invalid api success result');
        }

        return true;
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    private function getResponseStatus(array $data)
    {
        $success = $data['data'][0]['success'] ?? null;

        if (is_null($success)) {
            throw new \Exception('Cannot get success response status');
        }

        return boolval($success);
    }
}