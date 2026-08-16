# Identifiers (PHP)

> PHP implementation of **Codaminds Identifiers** — Specification-driven national document validator.

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/codaminds/identifiers.svg)](https://packagist.org/packages/codaminds/identifiers)

---

## Notice

> ⚠️ **Read-only Subtree Split:** This repository is a read-only mirror. Please submit issues and pull requests to the main monorepo at [Codaminds/identifiers](https://github.com/Codaminds/identifiers).

---

## Installation

Requires **PHP 8.2+**.

```bash
composer require codaminds/identifiers
``` 

## Usage

```php
use Codaminds\Identifiers\Identifier;

// National ID (Cédula)
$isValidId = Identifier::isValid('EC', 'national-id', '0926687856');

// Tax ID (RUC - Natural, Public, or Private)
$isValidRuc = Identifier::isValid('EC', 'tax-id', '0926687856001');

// Detailed validation
$result = Identifier::validate('EC', 'tax-id', '0926687856000');
if (!$result->isValid) {
    echo $result->errorCode;    // 'INVALID_ESTABLISHMENT'
    echo $result->errorMessage; // 'Establishment code must be greater than zero'
}
```

---

## Supported Identifiers

| Country       | Identifier | Code | Support |
|:--------------| :--- | :--- | :---: |
| 🇪🇨  Ecuador | Cédula de Identidad | `national-id` | ✅ |
| 🇪🇨 Ecuador | Registro Único de Contribuyentes (RUC) | `tax-id` | ✅ |

---

## License
MIT © [Codaminds](LICENSE).