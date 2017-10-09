<?php

namespace Conectra\Expressive\Client;

use Conectra\Expressive\Client\QueryBuilder\SelectBuilder;
use Conectra\Expressive\Client\QueryBuilder\SearchBuilder;
use Conectra\Expressive\Client\QueryBuilder\UpdateBuilder;
use Conectra\Expressive\Client\Transport\CurlTransport;

/**
 * Class ExpressiveClient
 *
 * @package Conectra\Expressive\Client
 */
abstract class ExpressiveClient
{

    /**
     * @var SelectBuilder
     */
    protected $selectBuilder;

    /**
     * @var SearchBuilder
     */
    protected $searchBuilder;

    /**
     * @var UpdateBuilder
     */
    protected $updateBuilder;

    /**
     * AbstractRepositoryClient constructor.
     *
     * @param CurlTransport $transport
     */
    public function __construct(CurlTransport $transport)
    {
        $this->selectBuilder = new SelectBuilder($transport);
        $this->searchBuilder = new SearchBuilder($transport);
        $this->updateBuilder = new UpdateBuilder($transport);
    }

    /**
     * @param array $arguments
     * @param array $options
     *
     * @return array|bool
     */
    public function select($arguments = [], $options = [])
    {
        return $this->selectBuilder->select([
                'arguments' => $arguments,
                'options'   => $options,
        ], $this);
    }

    /**
     * @return array|bool
     */
    public function search()
    {
        $args = $this->searchBuilder->getSelectArguments($this);

        $result = $this->selectBuilder->select($args, $this);

        return is_array($result) ? $result[0] : $result;
    }

    /**
     * @return bool
     */
    public function update()
    {
        return $this->updateBuilder->update($this);
    }
}