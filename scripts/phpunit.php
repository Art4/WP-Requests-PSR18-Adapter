<?php

declare(strict_types=1);

/**
 * Runs PHPUnit with the configuration file matching the installed PHPUnit version.
 *
 * PHPUnit 9.6 uses phpunit.legacy.xml, PHPUnit 10 and newer use phpunit.xml.
 *
 * Usage: composer run phpunit -- [phpunit arguments]
 */

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

$config = version_compare(\PHPUnit\Runner\Version::id(), '10', '<') ? 'phpunit.legacy.xml' : 'phpunit.xml';

$command = array_merge(
    [PHP_BINARY, $root . '/vendor/bin/phpunit', '--configuration', $root . '/' . $config],
    array_slice($argv, 1)
);

passthru(implode(' ', array_map('escapeshellarg', $command)), $exitCode);

exit($exitCode);
