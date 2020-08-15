<?php

use HaydenPierce\ClassFinder\ClassFinder;
use Illuminate\Support\Str;
use Wimski\Beatport\Contracts\RequestFilterInterface;
use Wimski\Beatport\Contracts\RequestSortInterface;
use Wimski\Beatport\Contracts\ResourceInterface;
use Wimski\Beatport\Enums\RequestTypeEnum;
use Wimski\Beatport\Requests\Filters\ResourceFilter;
use Wimski\Beatport\Resources\AbstractResource;

require __DIR__ . '/vendor/autoload.php';

$classesInResourcesNamespace = ClassFinder::getClassesInNamespace('Wimski\Beatport\Resources');

$resourceClasses = array_filter($classesInResourcesNamespace, function (string $class) {
    return is_subclass_of($class, AbstractResource::class);
});

foreach ($resourceClasses as $resourceClass) {
    /** @var ResourceInterface $resource */
    $resource = new $resourceClass();

    $resourceGenerator = new ResourceGenerator($resource);
    $data = $resourceGenerator->generate();

    file_put_contents(__DIR__ . '/docs/resources/' . $resource->type()->getValue() . '.md', $data);
}

class ResourceGenerator
{
    /**
     * @var ResourceInterface
     */
    protected $resource;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    public function generate(): string
    {
        return implode(PHP_EOL, [
            $this->getBreadcrumbs(),
            $this->getTitle(),
            $this->getRequests(),
            $this->getRelationships(),
            $this->getFilters(),
            $this->getSorts(),
        ]);
    }

    protected function getBreadcrumbs(): string
    {
        return implode(' \\ ', [
            '[Documentation](./../index.md)',
            '[Resources](./../resources.md)',
            $this->getResourceTitle($this->resource),
        ]);
    }

    protected function getTitle(): string
    {
        return '# ' . $this->getResourceTitle($this->resource);
    }

    protected function getRequests(): string
    {
        $data = ['## Requests'];

        if (method_exists($this->resource, 'all')) {
            $data[] = '* [Index](./../requests.md#index)';
        }

        if (method_exists($this->resource, 'relationship')) {
            $data[] = '* [Relate](./../requests.md#relate)';
        }

        if (method_exists($this->resource, 'search')) {
            $data[] = '* [Search](./../requests.md#search)';
        }

        if (method_exists($this->resource, 'view')) {
            $data[] = '* [View](./../requests.md#view)';
        }

        return implode(PHP_EOL, $data);
    }

    protected function getRelationships(): string
    {
        $data = ['## Relationships'];

        $relationships = $this->resource->relationships();

        if (! $relationships->count()) {
            $data[] = 'N/A';

            return implode(PHP_EOL, $data);
        }

        foreach ($relationships as $relationship) {
            /** @var ResourceInterface $resource */
            $resource = new $relationship();

            $title = $this->getResourceTitle($resource);
            $type  = $resource->type()->getValue();

            $data[] = "* [{$title}](./{$type}.md)";
        }

        return implode(PHP_EOL, $data);
    }

    protected function getFilters(): string
    {
        $data = ['## Filters'];

        $filters = $this->resource->indexFilters()
            ->concat($this->resource->relationshipFilters())
            ->concat($this->resource->searchFilters())
            ->unique(function (RequestFilterInterface $filter) {
                return $filter->name();
            })
            ->sortBy(function (RequestFilterInterface $filter) {
                return $filter->name();
            });

        if (! $filters->count()) {
            $data[] = 'N/A';

            return implode(PHP_EOL, $data);
        }

        $data[] = '| Name | Type | Index | Relationship | Search |';
        $data[] = '| ---- | ---- | :---: | :----------: | :----: |';

        /** @var RequestFilterInterface $filter */
        foreach ($filters as $filter) {
            $name = $filter->name();

            $className = str_replace('Filter', '', (new ReflectionClass($filter))->getShortName());
            $classNameLower = strtolower($className);
            $link = "[{$className}](./../filters.md#{$classNameLower})";

            if ($filter instanceof ResourceFilter && $filter->supportsMultipleValues()) {
                $link .= ' (multiple)';
            }

            $hasIndex  = $this->resource->getFilter(RequestTypeEnum::INDEX(), $name) ? 'x' : '';
            $hasRelate = $this->resource->getFilter(RequestTypeEnum::RELATIONSHIP(), $name) ? 'x' : '';
            $hasSearch = $this->resource->getFilter(RequestTypeEnum::QUERY(), $name) ? 'x' : '';

            $data[] = "| `{$name}` | {$link} | {$hasIndex} | {$hasRelate} | {$hasSearch} |";
        }

        return implode(PHP_EOL, $data);
    }

    protected function getSorts(): string
    {
        $data = ['## Sorts'];

        $sorts = $this->resource->indexSorts()
            ->concat($this->resource->relationshipSorts())
            ->concat($this->resource->searchSorts())
            ->unique(function (RequestSortInterface $sort) {
                return $sort->name();
            })
            ->sortBy(function (RequestSortInterface $sort) {
                return $sort->name();
            });

        if (! $sorts->count()) {
            $data[] = 'N/A';

            return implode(PHP_EOL, $data);
        }

        $data[] = '| Name | Index | Relationship | Search |';
        $data[] = '| ---- | :---: | :----------: | :----: |';

        /** @var RequestSortInterface $sort */
        foreach ($sorts as $sort) {
            $name = $sort->name();

            $hasIndex  = $this->resource->getSort(RequestTypeEnum::INDEX(), $name) ? 'x' : '';
            $hasRelate = $this->resource->getSort(RequestTypeEnum::RELATIONSHIP(), $name) ? 'x' : '';
            $hasSearch = $this->resource->getSort(RequestTypeEnum::QUERY(), $name) ? 'x' : '';

            $data[] = "| `{$name}` | {$hasIndex} | {$hasRelate} | {$hasSearch} |";
        }

        return implode(PHP_EOL, $data);
    }

    protected function getResourceTitle(ResourceInterface $resource): string
    {
        return str_replace('-', ' ', Str::title($resource->type()->getValue()));
    }
}
