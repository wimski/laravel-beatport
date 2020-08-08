<?php

namespace Wimski\Beatport\Processors;

use Wimski\Beatport\Enums\ResourceTypeEnum;
use Wimski\Beatport\Requests\Request;

class UrlProcessor
{
    public function process(string $url): array
    {
        preg_match("/^{$this->getRegex()}/", $url, $matches);

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
