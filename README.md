# Codaminds Identifiers

> Multi-language, specification-driven national identity document validator.

[![CI Suite](https://github.com/codaminds/identifiers/actions/workflows/ci.yml/badge.svg)](https://github.com/codaminds/identifiers/actions)
[![Packagist Version](https://img.shields.io/packagist/v/codaminds/identifiers.svg)](https://packagist.org/packages/codaminds/identifiers)
[![npm version](https://img.shields.io/npm/v/@codaminds/identifiers.svg)](https://www.npmjs.com/package/@codaminds/identifiers)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

---

## Monorepo Packages

| Ecosystem | Package | Repository / Registry | Install |
| :--- | :--- | :--- | :--- |
| **PHP** | `codaminds/identifiers` | [GitHub](https://github.com/Codaminds/identifiers-php) / [Packagist](https://packagist.org/packages/codaminds/identifiers) | `composer require codaminds/identifiers` |
| **TypeScript / JS** | `@codaminds/identifiers` | [NPM Registry](https://www.npmjs.com/package/@codaminds/identifiers) | `npm install @codaminds/identifiers` |

---

## Supported Specifications

| Country | Document Type | Code | PHP | TypeScript |
| :--- | :--- | :--- | :---: | :---: |
| 🇪🇨 Ecuador | Cédula de Identidad | `national-id` | ✅ | ✅ |

---

## Development Workflow

1. Update specifications or test fixtures under `test-vectors/`.
2. Implement validation logic across `packages/php` and `packages/typescript`.
3. Run test suites locally using Docker:
   ```bash
   docker compose run --rm php-app ./vendor/bin/phpunit
   docker compose run --rm node-app npm test
    ```

## License
MIT © [Codaminds](LICENSE).