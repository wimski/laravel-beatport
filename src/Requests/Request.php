<?php

namespace Wimski\Beatport\Requests;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ResourceProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ResourceProcessorInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Processors\PaginationProcessor;

class Request implements RequestInterface
{
    public const URL = 'https://www.beatport.com';

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
        ResourceProcessorFactoryInterface $resourceProcessorFactory,
        RequestConfig $config
    ) {
        $this->resourceProcessorFactory = $resourceProcessorFactory;
        $this->config                   = $config;

        $this->resourceProcessor = $this->resourceProcessorFactory->make($config->resourceType());

        $response = $this->request();

        if ($this->config->canHavePagination()) {
            $paginationProcessor = new PaginationProcessor();
            $this->pagination    = $paginationProcessor->process($response);
        }
    }

    public function data()
    {
        return $this->data;
    }

    public function paginate(string $action, int $amount = null): RequestInterface
    {
        if (! $this->pagination || ! method_exists($this->pagination, $action)) {
            return $this;
        }

        $this->pagination->{$action}($amount);

        $this->request();

        return $this;
    }

    public function url(): string
    {
        $url = static::URL . $this->config->path();

        $params = $this->config->queryParams();

        if ($this->pagination && $this->pagination->current() > 1) {
            $params['page'] = $this->pagination->current();
        }

        if (! empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    protected function request(): string
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->url(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Accept-Language: en-US,en',
            ],
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        $this->data = $this->resourceProcessor->process($this->config->requestType(), $response);

        return $response;
    }
}
