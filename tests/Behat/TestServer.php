<?php

declare(strict_types=1);

namespace Art4\Requests\Tests\Behat;

use RuntimeException;

/**
 * Runs tests/Behat/features/server/index.php with the PHP built-in server.
 *
 * The server stays on 127.0.0.1:8057, because its redirects use that address.
 */
final class TestServer
{
    private const HOST = '127.0.0.1';

    private const PORT = 8057;

    public const BASE_URL = 'http://' . self::HOST . ':' . self::PORT;

    private const START_TIMEOUT_SECONDS = 5.0;

    /**
     * @var resource|null
     */
    private $process;

    public function start(): void
    {
        $process = proc_open(
            [PHP_BINARY, '-dvariables_order=EGPCS', '-S', self::HOST . ':' . self::PORT, '-t', __DIR__ . '/features/server'],
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
            $socket = @fsockopen(self::HOST, self::PORT, $errorCode, $errorMessage, 0.2);

            if (is_resource($socket)) {
                fclose($socket);

                return;
            }

            usleep(50000);
        }

        $this->stop();

        throw new RuntimeException('The test server did not start listening on ' . self::BASE_URL . '.');
    }
}
