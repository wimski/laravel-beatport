<?php

namespace Wimski\Beatport\Requests;

use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ResourceProcessorInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Enums\PaginationActionEnum;
use Wimski\Beatport\Processors\PaginationProcessor;

class Request implements RequestInterface
{
    /**
     * @var ClientInterface
     */
    protected $guzzle;

    /**
     * @var ResourceProcessorFactoryInterface
     */
    protected $resourceProcessorFactory;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var RequestConfig
     */
    protected $requestConfig;

    /**
     * @var ResourceProcessorInterface
     */
    protected $resourceProcessor;

    /**
     * @var string
     */
    protected $response;

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
        Repository $config,
        RequestConfig $requestConfig
    ) {
        $this->guzzle                   = $guzzle;
        $this->resourceProcessorFactory = $resourceProcessorFactory;
        $this->config                   = $config;
        $this->requestConfig            = $requestConfig;

        $this->resourceProcessor = $this->resourceProcessorFactory->make($requestConfig->resourceType());

        $this->response = $this->request();

        if ($this->requestConfig->canHavePagination()) {
            $this->pagination = $paginationProcessor->process($this->response);
        }
    }

    public function response(): ?string
    {
        return $this->response;
    }

    public function data()
    {
        return $this->data;
    }

    public function paginate($action, int $amount = null): RequestInterface
    {
        if (! $this->hasPagination() || ! PaginationActionEnum::isValid($action)) {
            return $this;
        }

        $action = new PaginationActionEnum($action);

        $this->pagination->{$action->getValue()}($amount);

        $this->response = $this->request();

        return $this;
    }

    public function hasPagination(): bool
    {
        return $this->pagination !== null;
    }

    public function currentPage(): ?int
    {
        return $this->hasPagination() ? $this->pagination->current() : null;
    }

    public function totalPages(): ?int
    {
        return $this->hasPagination() ? $this->pagination->total() : null;
    }

    protected function request(): string
    {
        $url = $this->config->get('beatport.url') . $this->requestConfig->path();

        $params = $this->requestConfig->queryParams();

        if ($this->hasPagination() && $this->pagination->current() > 1) {
            $params['page'] = $this->pagination->current();
        }

        $response = $this->guzzle->request('GET', $url, [
            'headers' => [
                'Accept-Language' => 'en-US,en',
            ],
            'query' => $params,
        ])->getBody();

        $this->data = $this->resourceProcessor->process($this->requestConfig->requestType(), $response);

        return $response;
    }
}
