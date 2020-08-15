[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/wimski/laravel-beatport.svg?branch=master)](https://travis-ci.org/wimski/laravel-beatport)
[![Coverage Status](https://coveralls.io/repos/github/wimski/laravel-beatport/badge.svg?branch=master)](https://coveralls.io/github/wimski/laravel-beatport?branch=master)

# Laravel Beatport: A Website Based API

Beatport is, besides a webshop, a database for electronic music with high quality up-to-date content. Unfortunately there is no open API at this point. This package creates an API layer using requests on https://www.beatport.com.

## Installation

Via Composer:

``` bash
$ composer require wimski/laravel-beatport
```

Autodiscover may be used to register the service provider automatically.
Otherwise, you can manually register the service provider in `config/app.php`:

```php
<?php
   'providers' => [
        ...
        Wimski\Beatport\Providers\BeatportServiceProvider::class,
        ...
   ],
```

## Documentation

For a usage guide and reference, please see the [documentation](./docs/index.md).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
