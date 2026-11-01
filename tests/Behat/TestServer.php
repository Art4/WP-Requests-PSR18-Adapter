<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Behat;

use RuntimeException;

/**
 * Runs features/server/index.php with the PHP built-in server.
 *
 * The server stays on 127.0.0.1:8057, because its redirects use that address.
 */
final class TestServer
{
    public const BASE_URL = 'http://127.0.0.1:8057';

    private const START_TIMEOUT_SECONDS = 5.0;

    /**
     * @var resource|null
     */
    private $process;

    public function start(): void
    {
        $process = proc_open(
            [PHP_BINARY, '-dvariables_order=EGPCS', '-S', '127.0.0.1:8057', '-t', dirname(__DIR__, 2) . '/features/server'],
            [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']],
            $pipes,
            null,
            // A request that hangs must not block the following scenarios.
            ['PHP_CLI_SERVER_WORKERS' => '4']
        );

        if (!is_resource($process)) {
            throw new RuntimeException('The test server could not be started.');
        }

        $this->process = $process;
        $this->waitUntilReachable();
    }

    public function stop(): void
    {
        if ($this->process === null) {
            return;
        }

        proc_terminate($this->process);
        proc_close($this->process);
        $this->process = null;
    }

    private function waitUntilReachable(): void
    {
        $deadline = microtime(true) + self::START_TIMEOUT_SECONDS;

        while (microtime(true) < $deadline) {
            $socket = @fsockopen('127.0.0.1', 8057, $errorCode, $errorMessage, 0.2);

            if (is_resource($socket)) {
                fclose($socket);

                return;
            }

            usleep(50000);
        }

        $this->stop();

        throw new RuntimeException('The test server did not start listening on 127.0.0.1:8057.');
    }
}
