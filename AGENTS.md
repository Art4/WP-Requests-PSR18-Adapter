# Agent Guidelines

## Project: WP-Requests-PSR18-Adapter

### Docker setup

**All PHP commands run in Docker containers. PHP on the host is never used.**

`docker-compose.yml` defines one service per supported PHP version (`docker compose config --services` lists them, e.g. `php84` for PHP 8.4). All services are built from `.docker/php.Dockerfile`.

| Command | Invocation |
|---------|------------|
| Install/update dependencies | `docker compose run --rm php84 composer update` |
| PHPUnit | `docker compose run --rm php84 composer phpunit` |
| PHPStan | `docker compose run --rm php84 composer phpstan` |
| Code style | `docker compose run --rm php84 composer codestyle -- --dry-run --diff` |
| Rector (proposes refactorings, not part of `composer test`) | `docker compose run --rm php84 composer rector -- --dry-run` |
| Behat (real HTTP requests, curl and fsockopen) | `docker compose run --rm php84 composer behat` |
| Everything (PHPStan, PHPUnit, code style) | `docker compose run --rm php84 composer test` |
| PHPUnit on all PHP versions | `scripts/test-all` |
| Any script on all PHP versions | `scripts/test-all test` |
| Only some PHP versions | `PHP_VERSIONS="php74 php85" scripts/test-all` |

Notes:
- **PHPUnit configuration:** `composer phpunit` runs `scripts/phpunit.php`, which uses `phpunit.legacy.xml` for PHPUnit 9.6 and `phpunit.xml` for PHPUnit 10 and newer.
- **composer.lock is not used** (`"lock": false` in `composer.json`). CI and local runs always resolve the latest allowed versions. New releases of dev tools can turn CI red without a code change – reproduce with `composer update`.
- **`vendor/` is separate for every PHP version** (`.docker/vendor/<service>`, mounted to `/app/vendor`; the directories are kept in Git with a `.gitkeep` so they belong to the host user). Run `composer update` once per service before running tests there. `scripts/test-all` does this for you.
- Containers run as the host user (`DOCKER_UID`/`DOCKER_GID`, default `1000`). Set them if your user id is different.
- After changing `.docker/php.Dockerfile`, rebuild: `docker compose build`.
- PHPStan and php-cs-fixer need a recent PHP version; they run in CI on PHP 8.4 and 8.3. php-cs-fixer does not run on PHP 8.6 yet, so `composer test` fails in `php86`.
- **PHP 8.7** runs in CI only, as a nightly build with `--ignore-platform-reqs` and `continue-on-error`. There is no Docker image for it yet.
- **Behat runs on PHP 8.4 only** and is not part of `composer test`. `tests/Behat/TestServer.php` starts `tests/Behat/features/server/index.php` (vendored from Symfony, keep it unchanged except for the `/echo` route) on `127.0.0.1:8057`.
- **Scenarios describe the desired behavior.** A failing scenario gets `@known-issue` (or `@known-issue-curl`, `@known-issue-fsockopen`) and an `@issue-<number>` tag, and the filter in `tests/Behat/behat.php` skips it. A fix removes the tag. The profiles `curl-known-issues` and `fsockopen-known-issues` run only these scenarios and are expected to fail: `docker compose run --rm php84 timeout 60 vendor/bin/behat --config=tests/Behat/behat.php --profile=curl-known-issues --no-interaction`. Keep the `timeout`, because an endless response never ends on fsockopen.
