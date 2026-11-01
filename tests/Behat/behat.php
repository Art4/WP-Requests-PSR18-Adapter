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
$profile = static function (string $name, string $transport, string $tags): Profile {
    return (new Profile($name))->withSuite(
        (new Suite('default'))
            ->withPaths('features')
            ->withFilter(new TagFilter($tags))
            ->addContext(HttpClientContext::class, ['transport' => $transport])
    );
};

$config = new Config();

foreach (['curl', 'fsockopen'] as $transport) {
    $config = $config
        ->withProfile($profile($transport, $transport, '~@known-issue&&~@known-issue-' . $transport))
        ->withProfile($profile($transport . '-known-issues', $transport, '@known-issue,@known-issue-' . $transport));
}

return $config;
