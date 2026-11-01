<?php

declare(strict_types=1);

use Art4\Requests\Tests\Behat\HttpClientContext;
use Behat\Config\Config;
use Behat\Config\Filter\TagFilter;
use Behat\Config\Profile;
use Behat\Config\Suite;

// Scenarios describe the desired behavior. Scenarios that fail today are tagged
// with @known-issue (all transports) or @known-issue-<transport> and are listed
// in the issue they refer to. A fix removes the tag.
$profile = static function (string $transport): Profile {
    return (new Profile($transport))->withSuite(
        (new Suite('default'))
            ->withPaths('features')
            ->withFilter(new TagFilter('~@known-issue&&~@known-issue-' . $transport))
            ->addContext(HttpClientContext::class, ['transport' => $transport])
    );
};

return (new Config())
    ->withProfile($profile('curl'))
    ->withProfile($profile('fsockopen'));
