<?php

namespace Wimski\Beatport\Processors;

use Illuminate\Contracts\Config\Repository;
use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceUrlException;

class ResourceUrlProcessor
{
    /**
     * @var Repository
     */
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function processResourceAttributes(string $url): array
    {
        if (! preg_match("/^{$this->getRegex()}/", $url, $matches)) {
            throw new InvalidResourceUrlException("{$url} is not a valid resource URL");
        }

        return [
            'type' => new ResourceTypeEnum($matches[1]),
            'slug' => $matches[2],
            'id'   => (int) $matches[3],
        ];
    }

    protected function getRegex(): string
    {
        $url = $this->config->get('beatport.url');

        $regex =  '(?:' . preg_quote($url, '/') . ')?\/?';
        $regex .= '(' . implode('|', ResourceTypeEnum::values()) . ')\/?';
        $regex .= '([a-z0-9\-\%]+)\/?';
        $regex .= '(\d+)\/?';

        return $regex;
    }
}
