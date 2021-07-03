# coverage-check

<p align="center">
    <img src="https://static.permafrost.dev/images/coverage-check/coverage-check-logo-alt.png" alt="coverage-check logo" height="200" style="block">
    <br><br>
    <img src="https://img.shields.io/packagist/v/permafrost-dev/coverage-check.svg" alt="Packagist Version">
    <img src="https://img.shields.io/github/license/permafrost-dev/coverage-check.svg" alt="license">
    <img src="https://github.com/permafrost-dev/coverage-check/actions/workflows/run-tests.yml/badge.svg?branch=main" alt="Test Run Status">
</p>

---

Display the code coverage for a project using a clover.xml file, optionally enforcing a minimum code coverage percentage.

Designed to be used in your CI/CD process.

The concept for this package is based on [this article](https://ocramius.github.io/blog/automated-code-coverage-check-for-github-pull-requests-with-travis/).

---

## Installation

```bash
composer require permafrost-dev/coverage-check --dev
```

## Usage

Specify a valid clover.xml file and (optionally) a minimum coverage percentage to require using the `--require` or `-r` flag.  A percentage can be either a whole number (integer) or a decimal (float).

If you specify the `--require/-r` flag, the check will fail if coverage is below the percentage you provide, and the process exit code will be non-zero.

If you don't specify the `--require/-r` flag, only the percentage of code coverage will be displayed.

```bash
./vendor/bin/coverage-check clover.xml
./vendor/bin/coverage-check clover.xml --require=50
./vendor/bin/coverage-check clover.xml -r 80.5
```

## Generating clover-format coverage files

PHPUnit can generate coverage reports in clover format:

```bash
./vendor/bin/phpunit --coverage-clover clover.xml
```

## Sample Output

![image](https://user-images.githubusercontent.com/5508707/124333695-ff36ee00-db62-11eb-9c13-07d9dad20ac9.png)

![image](https://user-images.githubusercontent.com/5508707/124333718-1249be00-db63-11eb-9a12-1c48680672da.png)

## Sample Github Workflow

```yaml
name: run-tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ${{ matrix.os }}
    strategy:
      fail-fast: true
      matrix:
        os: [ubuntu-latest]
        php: [8.0, 7.4, 7.3]

    name: P${{ matrix.php }} - ${{ matrix.stability }} - ${{ matrix.os }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v2

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, intl, iconv, fileinfo
          coverage: none

      - name: Setup problem matchers
        run: |
          echo "::add-matcher::${{ runner.tool_cache }}/php.json"
          echo "::add-matcher::${{ runner.tool_cache }}/phpunit.json"

      - name: Install dependencies
        run: composer update --prefer-stable --prefer-dist --no-interaction

      - name: Execute tests
        run: ./vendor/bin/phpunit --coverage-clover clover.xml

      - name: Enforce 75% code coverage
        run: ./vendor/bin/coverage-check clover.xml --require=75
```

## Testing

```bash
./vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Patrick Organ](https://github.com/patinthehat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
