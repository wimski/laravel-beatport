[Documentation](./index.md) \ Filters

# Filters

* [BPM](#bpm)
* [Date](#date)
* [Preorder](#preorder)
* [Resource](#resource)
* [Type](#type)

## BPM

The input for the BPM filter must an array with the keys `low` and `high`. Both values must be an integer and the `low` value must be less than or equal to the `high` value.

```php
$builder->filter('bpm', [
    'low'  => 120,
    'high' => 140,
]);
```

## Date

The input for the date filter can be either a string, a `DateFilterPresetEnum` instance or an array. When the input is a string, it has to be a valid `DateFilterPresetEnum` value. When the input is an array, the keys must be `start` and `end`. Both values must be supported by [Carbon](https://carbon.nesbot.com/docs/#api-instantiation)'s `parse` method and the `start` value must be less than or equal to the `end` value.

```php
$builder->filter('date', '7d');
// or
$builder->filter('date', [
    'start' => '2020-07-01',
    'end'   => '2020-07-31',
]);
```

## Preorder

The input for the preorder filter must be a boolean.

```php
// This will include preorders in the results
$builder->filter('preorder', true);
```

## Resource

The input for the resource can either be an integer or an array of integers, depending if the specific filter supports multiple values. If the filter supports multiple values, you are still allowed to just input an integer, but not the other way around. The name of the filter is specified by the available filters for each resource.

```php
$builder->filter('artists', [23, 66, 105]);
$builder->filter('label', 234);
```

## Type

The input for the type filter must either be an int or a `TypeFilterPresetEnum` instance. When the input is an int, it has to be a valid `TypeFilterPresetEnum` value.

```php
$builder->filter('type', 2);
```
