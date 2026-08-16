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

// Quick Boolean check
$isValid = Identifier::isValid('EC', 'national-id', '0926687856'); // true

// Detailed validation result
$result = Identifier::validate('EC', 'national-id', '0926687857');

if (!$result->isValid) {
    echo $result->errorCode;    // 'INVALID_CHECKSUM'
    echo $result->errorMessage; // 'Verification digit does not match Luhn mod 10 algorithm'
}
```

---

## Supported Identifiers

| Country       | Identifier | Code | PHP Support |
|:--------------| :--- | :--- | :---: |
| 🇪🇨  Ecuador | Cédula de Identidad | `national-id` | ✅ |

---

## License
This project is licensed under the [MIT License](LICENSE).