<?php

namespace Conectra\Expressive\Client\QueryBuilder;

use Conectra\Expressive\Client\Transport\CurlTransport;

/**
 * Class SearchBuilder
 *
 * @package MsBoletos\Schemas\Titulo\Client
 */
class SearchBuilder
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
     * @param $model
     *
     * @return array
     */
    public function getSelectArguments($model)
    {
        return [
                'arguments' => $this->getKeysAsArguments($model),
        ];
    }

    /**
     * @param $model
     *
     * @return array
     * @throws \Exception
     */
    private function getKeysAsArguments($model)
    {
        $keys = $model->getSchema()->getKeys();

        $arguments = [];
        foreach ($keys as $property) {
            $arguments[] = $this->getBindingFromModel($model, $property);
        }

        return $arguments;
    }

    /**
     * @param $model
     * @param $property
     *
     * @return array
     * @throws \Exception
     */
    private function getBindingFromModel($model, $property): array
    {
        $value = $model->{$property->getProperty()};

        if (is_null($value)) {
            throw new \Exception('A mandatory property cannot be null while in search method');
        }

        return [
                'column' => $property->getField(),
                'value'  => $value,
        ];
    }
}