[Documentation](./index.md) \ Requests

# Requests

* [Types](#types)
    * [Index](#index)
    * [Relate](#relate)
    * [Search](#search)
    * [View](#view)
* [Filtering](#filtering)
* [Sorting](#sorting)
* [Pagination](#pagination)

[Resources](./resources.md) have static methods to start building different [types](#types) of requests. Depending on the resource and request type, you can [filter](#filtering), [sort](#sorting) and use [pagination](#pagination). Here is an example of getting all tracks for a certain label ordered by newest release in chunks of 50 per request.

```php
$request = TrackResource::all()
    ->filter('label', 56833)
    ->sort('release', 'desc')
    ->pageSize(50)
    ->get();

$tracks = $request->data();

while ($request->currentPage() !== $request->totalPages()) {
    $tracks = $tracks->concat(
        $request->paginate('next')->data(),
    );
}
```

## Types

There are four types of requests. Most requests return a collection of `DataInterface` as data and supports [filtering](#filtering), [sorting](#sorting) and [pagination](#pagination), but the [View](#view) type is the only one that returns a single `DataInterface` object and doesn't have any modifiers.
Each type has a method associated with it to start building a request. You will notice however that both the [Relate](#relate) and [View](#view) type have a variation method whereby a `DataInterface` object can be used as an argument instead of a separate `$slug` and `$id`. This can be useful if you want to request relationships of a resource for which you already have the view data, or when you want to enrich a single resource's data. Here are some examples.

```php
// Get a release
$release = ReleaseResource::find('some-release-name', 123)->get()->data();

// The Label is missing artwork when retrieved from a release
// Now we complete the label using the data from the release
$label = LabelResource::findByData($release->getLabel())->get()->data();

// Get the related tracks for the label using the data object
$tracks = LabelResource::relationshipByData($label, TrackResource::class)->get()->data();
```

### Index

```php
ResourceInterface::all(): IndexRequestBuilder;
```

### Relate

```php
ResourceInterface::relationship(string $slug, int $id, string $relationship): RelationshipRequestBuilder;
ResourceInterface::relationshipByData(DataInterface $data, string $relationship): RelationshipRequestBuilder;
```

The `$relationship` argument must be a fully qualified class name of a resource that is available as a relationship for the requested resource. Check the [resources](./resources.md) reference to see which type supports which relationships (if any). Here is an example of creating a request builder to get the related tracks of a label.

```php
$builder = LabelResource::relationship('slug', 123, TrackResource::class);
```

### Search

```php
ResourceInterface::search(string $query): SearchRequestBuilder: 
```

The `$query` argument must not be URL-encoded. The actual request will take care of that.

### View

```php
ResourceInterface::find(string $slug, int $id): ViewRequestBuilder;
ResourceInterface::findByData(DataInterface $data): ViewRequestBuilder;
```

## Filtering

Filters can be added to a request by the `filter` method. This method is fluent a can therefore be chained. It expects two arguments: `$name` and `$value`.

### Name Argument

The `$name` argument must be a string. If the requested resource cannot find a filter with this name for the current request type, an exception is thrown. Check out the [resources](./resources.md) reference to see which filters are available per resource and request type. A specific filter can only be applied once. So if you call the same filter multiple times with different values, only the last value will be used.

```php
// The request will use a value of 2 for filter 'name'
$builder
    ->filter('name', 1)
    ->filter('name', 2);
```

### Value Argument

The type of the `$value` argument depends on the type of filter. The filter itself will validate the value input and throw an exception if the type is not correct. Check the [filters](./filters.md) reference to see what kind of values each filter requires.

## Sorting

Sorting can be added to a request by the `sort` method. This method is fluent a can therefore be chained. It expects two arguments: `$name` and `$direction`. There can only be one sort applied at a time. So if you call the sort method multiple times, only the last sorting will be used.

```php
// The request will sort by 'bar' descending
$builder
    ->sort('foo')
    ->sort('bar', 'desc');
```

### Name Argument

The `$name` argument must be a string. If the requested resource cannot find a sort with this name for the current request type, an exception is thrown. Check out the [resources](./resources.md) reference to see which sorts are available per resource and request type.

### Direction Argument

The `$direction` argument must be a string and one of the following two values: `asc` or `desc`. The default value is `asc`.

## Pagination

Pagination is available depending on the request type and if the number of returned results of the first request is greater than the page size.

### Page Size

The page size can be set on the builder with the `pageSize` method. The `$pageSize` argument can either be a `RequestPageSizeEnum` instance or a string. The string has to be a valid `RequestPageSizeEnum` value. The default value is `25`.

### Available methods

```php
$request->hasPagination(); // returns true or false
$request->currentPage(); // returns the current page or null
$request->totalPages(); // returns the total number of pages or null
$request->paginate('page', 3); // applies a pagination action
```

The paginate method can receive two arguments: `$action` and `$amount`. The `$action` argument can either be a `PaginationActionEnum` instance or a string. The string has to be a valid `PaginationActionEnum` value. The following actions can receive the `$amount` argument which must be an integer:

* `page`: the `$amount` argument specifies the page to load
* `add`: the `$amount` argument specifies how many pages to add relative to the current page
* `sub`: the `$amount` argument specifies how many pages to subtract relative to the current page