# Agent Guidelines

## Project: WP-Requests-PSR18-Adapter

### Docker setup

**All PHP commands run in Docker containers. PHP on the host is never used.**

`docker-compose.yml` defines one service per supported PHP version: `php72`, `php73`, `php74`, `php80`, `php81`, `php82`, `php83`, `php84`, `php85`. All services are built from `.docker/php.Dockerfile`.

| Command | Invocation |
|---------|------------|
| Install/update dependencies | `docker compose run --rm php84 composer update` |
| PHPUnit | `docker compose run --rm php84 composer phpunit` |
| PHPStan | `docker compose run --rm php84 composer phpstan` |
| Code style | `docker compose run --rm php84 composer codestyle -- --dry-run --diff` |
| Everything (PHPStan, PHPUnit, code style) | `docker compose run --rm php84 composer test` |
| PHPUnit on all PHP versions | `scripts/test-all` |
| Any script on all PHP versions | `scripts/test-all test` |
| Only some PHP versions | `PHP_VERSIONS="php72 php85" scripts/test-all` |

Notes:
- **composer.lock is not used** (`"lock": false` in `composer.json`). CI and local runs always resolve the latest allowed versions. New releases of dev tools can turn CI red without a code change – reproduce with `composer update`.
- **`vendor/` is separate for every PHP version** (`.docker/vendor/<service>`, mounted to `/app/vendor`). Run `composer update` once per service before running tests there. `scripts/test-all` does this for you.
- Containers run as the host user (`DOCKER_UID`/`DOCKER_GID`, default `1000`). Set them if your user id is different.
- After changing `.docker/php.Dockerfile`, rebuild: `docker compose build`.
- PHPStan and php-cs-fixer need a recent PHP version; they run in CI on PHP 8.4 and 8.3.
