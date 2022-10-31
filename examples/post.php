<?php

// First, include the Composer Autoloader.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Next, create the HTTP client
$client = new \Art4\Requests\Psr\HttpClient();

// Next, create the HTTP request
$request = $client->createRequest(\WpOrg\Requests\Requests::POST, 'http://httpbin.org/post');

// You can set headers in PSR-7 style
$request = $request->withAddedHeader('Accept', 'application/json');

// You can set form data as body
$request = $request->withBody($client->createStream(
    http_build_query(['mydata' => 'something'])
));

// Now let's make a request!
$response = $client->sendRequest($request);

// Check what we received
var_dump($response);
