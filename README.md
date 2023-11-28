# WordPress/Requests PSR-18 Adapter

[![Latest Version](https://img.shields.io/github/release/Art4/WP-Requests-PSR18-Adapter.svg)](https://github.com/Art4/WP-Requests-PSR18-Adapter/releases)
[![Software License](https://img.shields.io/badge/license-GPL3-brightgreen.svg)](LICENSE.md)
[![Build Status](https://github.com/Art4/WP-Requests-PSR18-Adapter/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/Art4/WP-Requests-PSR18-Adapter/actions)
[![codecov](https://codecov.io/gh/Art4/WP-Requests-PSR18-Adapter/branch/main/graph/badge.svg?token=NWWJXG6MIL)](https://codecov.io/gh/Art4/WP-Requests-PSR18-Adapter)
[![Total Downloads](https://img.shields.io/packagist/dt/art4/requests-psr18-adapter.svg)](https://packagist.org/packages/art4/requests-psr18-adapter)

Use [WordPress/Requests](https://github.com/WordPress/Requests) as a [PSR-18](https://www.php-fig.org/psr/psr-18/) HTTP client adapter.

- Requires PHP 7.2+
- Supports Requests v1.8+ and v2

## Why?

Requests is a HTTP library written in PHP, that [lacks of support for PSR-7](https://github.com/WordPress/Requests/issues/320) and also for PSR-18 because of the compatability with PHP 5.6+.

[I've created a PR in Requests to add PSR-7 support](https://github.com/WordPress/Requests/pull/768) but this would add new direct dependencies to Requests. So I created this library as an optional wrapper for Requests. If one day Requests nativly supports PSR-7 and PSR-18, this library might become obsolete.

## How to use

### Installation with Composer

WordPress/Requests PSR-18 Adapter is [available on Packagist](https://packagist.org/packages/art4/requests-psr18-adapter) and can be installed using [Composer](https://getcomposer.org/).

```bash
composer require art4/requests-psr18-adapter
```

If you want to use WordPress/Requests PSR-18 Adapter in context of a WordPress instance (e.g. in a plugin or theme) you should add `"rmccue/requests": "*"` as a `replace` package link. This will prevent composer from installing `rmccue/requests` two times, leading to fatal errors.

Example `composer.json`:

```json
{
    "require": {
        "art4/requests-psr18-adapter": "^1.1"
    },
    "replace": {
        "rmccue/requests": "*"
    }
}
```

### Examples

Take a look at the [examples directory](examples/) for more examples.


```php
<?php

// First, include the Composer autoload.php
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define Requests options
$options = [
    'proxy' => '127.0.0.1:8080',
    'transport' => $customTransport,
    // other Requests options
];

// Create the HTTP client
$httpClient = new \Art4\Requests\Psr\HttpClient($options);

// Create a PSR-7 request and optional set other headers
$request = $httpClient->createRequest('GET', 'http://httpbin.org/get');
$request = $request->withHeader('Accept', 'application/json');

try {
    // Send the request
    $response = $httpClient->sendRequest($request);
} catch (\Psr\Http\Client\ClientExceptionInterface $th) {
    // Handle errors
    throw $th;
}

// Use the PSR-7 Response
var_dump($response->getBody()->__toString());
```
