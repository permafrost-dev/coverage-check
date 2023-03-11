# coverage-check

<p align="center">
    <img src="https://static.permafrost.dev/images/coverage-check/coverage-check-logo-alt.png" alt="coverage-check logo" height="200" style="block">
    <br><br>
    <img src="https://img.shields.io/github/v/release/permafrost-dev/coverage-check.svg?sort=semver&logo=github" alt="Package Version">
    <img src="https://img.shields.io/packagist/dt/permafrost-dev/coverage-check?logo=packagist&logoColor=%23fff" alt="Downloads">
    <img src="https://github.com/permafrost-dev/coverage-check/actions/workflows/run-tests.yml/badge.svg?branch=main" alt="Test Run Status">
    <br>
    <img src="https://img.shields.io/github/license/permafrost-dev/coverage-check.svg?logo=opensourceinitiative" alt="license">
    <img src="https://codecov.io/gh/permafrost-dev/coverage-check/branch/main/graph/badge.svg?token=Xau3YK5548" alt="code coverage">
</p>

---

Display the code coverage for a project using a clover.xml file, optionally enforcing a minimum code coverage percentage.

This package is designed to be used in your CI/CD or automated testing process _(i.e., using GitHub Workflows)_.

The concept for this package is based on [this article](https://ocramius.github.io/blog/automated-code-coverage-check-for-github-pull-requests-with-travis/).

---

## Installation

```bash
composer require permafrost-dev/coverage-check --dev
```

## Usage

Specify a valid clover.xml file and (optionally) a minimum coverage percentage to require using the `--require` or `-r` flag.  A percentage can be either a whole number (integer) or a decimal (float).

If you specify the `--require/-r` flag, the check will fail if coverage percent is below the value you provide, and the process exit code will be non-zero.

If you don't specify the `--require/-r` flag, only the percentage of code coverage will be displayed and the exit code will always be zero.

```bash
./vendor/bin/coverage-check clover.xml
./vendor/bin/coverage-check clover.xml --require=50
./vendor/bin/coverage-check clover.xml -r 80.5
./vendor/bin/coverage-check clover.xml -m statement -r 75
./vendor/bin/coverage-check clover.xml --precision=1
```

## Available Options

| Option | Description |
| --- | --- |
| `--coverage-only` or `-C` | Only display the code coverage value |
| `--metric` or `-m` `<name>` | Use the specified metric field for calculating coverage. Valid values are `element` _(default)_, `method`, or `statement` |
| `--precision` or `-p` `<value>` | Use the specified precision when calculating the code coverage percentage, where `<value>` is an integer _(default: 4)_ |
| `--require` or `-r` `<value>` | Enforce a minimum code coverage value, where `<value>` is an integer or decimal value |

## Metric fields

The field that is used to calculate code coverage can be specified using the `--metric=<name>` or `-m <name>` option.

Valid field names are `element` _(the default)_, `statement`, and `method`.

## Generating clover-format coverage files

PHPUnit can generate coverage reports in clover format:

```bash
./vendor/bin/phpunit --coverage-clover clover.xml
```

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
        php: [8.1, 8.0, 7.4, 7.3]

    name: P${{ matrix.php }} - ${{ matrix.os }}

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, intl, iconv, fileinfo
          coverage: pcov

      - name: Setup problem matchers
        run: |
          echo "::add-matcher::${{ runner.tool_cache }}/php.json"
          echo "::add-matcher::${{ runner.tool_cache }}/phpunit.json"

      - name: Install dependencies
        run: composer update --prefer-stable --prefer-dist --no-interaction

      - name: Execute tests
        run: ./vendor/bin/phpunit --coverage-clover clover.xml

      - name: Enforce 75% code coverage
        run: ./vendor/bin/coverage-check clover.xml --require=75 --precision=2
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
