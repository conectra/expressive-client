<?php

namespace Conectra\Expressive\Client\QueryBuilder;

use Conectra\Expressive\Client\Transport\CurlTransport;

/**
 * Class SelectBuilder
 *
 * @package MsBoletos\Schemas\Titulo\Client
 */
class SelectBuilder
{
    /**
     * @var CurlTransport
     */
    private $transport;

    /**
     * SelectBuilder constructor.
     *
     * @param CurlTransport $transport
     */
    public function __construct(CurlTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param array $args
     * @param mixed $model
     *
     * @return array|bool
     */
    public function select(array $args = [], $model)
    {
        $response = $this->transport->get($args, 'select');

        return $this->handleApiResponse($response, get_class($model));
    }

    /**
     * @param array  $response
     * @param string $class
     *
     * @return array|bool
     */
    private function handleApiResponse(array $response, string $class)
    {
        $this->checkValidStatus($response);

        $data = $response['data'] ?: false;

        return $data && is_array($data) ? $this->getDataInstances($data, $class) : $data;
    }

    /**
     * @param array $response
     *
     * @return bool
     * @throws \Exception
     */
    private function checkValidStatus(array $response)
    {
        $status = $response['status'] ?? null;

        if (is_null($status)) {
            $message = $response['message'] ?? 'Api invalid response';
            throw new \Exception($message);
        }

        return true;
    }

    /**
     * @param array  $data
     * @param string $class
     *
     * @return array
     */
    private function getDataInstances(array $data, string $class): array
    {
        $instances = [];
        foreach ($data as $model) {
            $instances[] = call_user_func_array([$class, 'make'], [$model]);
        }

        return $instances;
    }
}