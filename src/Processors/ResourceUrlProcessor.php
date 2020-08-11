<?php

namespace Wimski\Beatport\Processors;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Exceptions\InvalidResourceUrlException;
use Wimski\Beatport\Requests\Request;

class ResourceUrlProcessor
{
    public function processResourceAttributes(string $url): array
    {
        if (! preg_match("/^{$this->getRegex()}/", $url, $matches)) {
            throw new InvalidResourceUrlException("{$url} is not a valid resource URL");
        }

        return [
            'type' => new ResourceTypeEnum($matches[1]),
            'slug' => $matches[2],
            'id'   => $matches[3],
        ];
    }

    protected function getRegex(): string
    {
        $url = Request::URL;

        $regex =  '(?:' . preg_quote($url, '/') . ')?\/?';
        $regex .= '(' . implode('|', ResourceTypeEnum::values()) . ')\/?';
        $regex .= '([a-z0-9\-\%]+)\/?';
        $regex .= '(\d+)\/?';

        return $regex;
    }
}
