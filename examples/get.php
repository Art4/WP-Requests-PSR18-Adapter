<?php

// First, include the Composer Autoloader.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// First, create the HTTP client
$client = new \Art4\Requests\Psr\HttpClient();

// Next, create the HTTP request
$request = $client->createRequest('GET', 'http://httpbin.org/get');

// You can set headers in PSR-7 style
$request = $request->withAddedHeader('Accept', 'application/json');

// Now let's make a request!
$response = $client->sendRequest($request);

// Check what we received
var_dump($response);
