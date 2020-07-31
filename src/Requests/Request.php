<?php

namespace Wimski\Beatport\Requests;

use Illuminate\Support\Collection;
use Wimski\Beatport\Contracts\DataInterface;
use Wimski\Beatport\Contracts\ProcessorFactoryInterface;
use Wimski\Beatport\Contracts\ProcessorInterface;
use Wimski\Beatport\Contracts\RequestBuilderInterface;
use Wimski\Beatport\Contracts\RequestInterface;
use Wimski\Beatport\Processors\PaginationProcessor;

class Request implements RequestInterface
{
    public const URL = 'https://www.beatport.com';

    /**
     * @var ProcessorFactoryInterface
     */
    protected $processorFactory;

    /**
     * @var RequestBuilderInterface
     */
    protected $builder;

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * @var Collection<DataInterface>|DataInterface|null
     */
    protected $data;

    /**
     * @var Pagination
     */
    protected $pagination;

    public function __construct(ProcessorFactoryInterface $processorFactory, RequestBuilderInterface $builder) {
        $this->processorFactory = $processorFactory;
        $this->builder          = $builder;

        $this->processor = $this->processorFactory->make($builder->resource());

        $response = $this->request();

        if ($this->builder->multipleResults()) {
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
        $url = static::URL . $this->builder->path();

        $params = $this->builder->queryParams();

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

        $this->data = $this->processor->process($response, $this->builder->multipleResults());

        return $response;
    }
}
