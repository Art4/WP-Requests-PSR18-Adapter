# WordPress/Requests PSR-18 Adapter

Use [WordPress/Requests](https://github.com/WordPress/Requests) as a [PSR-18](https://www.php-fig.org/psr/psr-18/) HTTP client adapter.

Requires PHP 7.1+

## Why?

Requests is a HTTP library written in PHP, that [lacks of support for PSR-7](https://github.com/WordPress/Requests/issues/320) and also for PSR-18 because of the compatability with PHP 5.6+.

[I've created a PR in Requests to add PSR-7 support](https://github.com/WordPress/Requests/pull/768) but I would be able to use it today. So I created this library. If one day Requests nativly supports PSR-7 and PSR-18, this library will become obsolete.

## How to use

### Installation with Composer

WordPress/Requests PSR-18 Adapter is [available on Packagist](https://packagist.org/packages/art4/requests-psr18-adapter) and can be installed using [Composer](https://getcomposer.org/).

```bash
composer require art4/requests-psr18-adapter
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
var_dump($response);
```
