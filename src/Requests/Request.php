<?php

namespace Wimski\Beatport\Requests;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ResourceProcessorInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Enums\PaginationActionEnum;
use Wimski\Beatport\Processors\PaginationProcessor;

class Request implements RequestInterface
{
    public const URL = 'https://www.beatport.com';

    /**
     * @var ClientInterface
     */
    protected $guzzle;

    /**
     * @var ResourceProcessorFactoryInterface
     */
    protected $resourceProcessorFactory;

    /**
     * @var RequestConfig
     */
    protected $config;

    /**
     * @var ResourceProcessorInterface
     */
    protected $resourceProcessor;

    /**
     * @var Collection<DataInterface>|DataInterface|null
     */
    protected $data;

    /**
     * @var Pagination
     */
    protected $pagination;

    public function __construct(
        ClientInterface $guzzle,
        ResourceProcessorFactoryInterface $resourceProcessorFactory,
        PaginationProcessor $paginationProcessor,
        RequestConfig $config
    ) {
        $this->guzzle                   = $guzzle;
        $this->resourceProcessorFactory = $resourceProcessorFactory;
        $this->config                   = $config;

        $this->resourceProcessor = $this->resourceProcessorFactory->make($config->resourceType());

        $response = $this->request();

        if ($this->config->canHavePagination()) {
            $this->pagination = $paginationProcessor->process($response);
        }
    }

    public function data()
    {
        return $this->data;
    }

    public function paginate(PaginationActionEnum $action, int $amount = null): RequestInterface
    {
        if (! $this->pagination) {
            return $this;
        }

        $this->pagination->{$action->getValue()}($amount);

        $this->request();

        return $this;
    }

    protected function request(): string
    {
        $url = static::URL . $this->config->path();

        $params = $this->config->queryParams();

        if ($this->pagination && $this->pagination->current() > 1) {
            $params['page'] = $this->pagination->current();
        }

        $response = $this->guzzle->request('GET', $url, [
            'headers' => [
                'Accept-Language' => 'en-US,en',
            ],
            'query' => $params,
        ])->getBody();

        $this->data = $this->resourceProcessor->process($this->config->requestType(), $response);

        return $response;
    }
}
