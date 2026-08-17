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

### Error Codes Reference

| Error Code | Description | Applicable Identifiers |
| :--- | :--- | :--- |
| `INVALID_FORMAT` | Value contains non-numeric characters or incorrect pattern | `national-id`, `tax-id` |
| `INVALID_LENGTH` | Length differs from the exact expected digit count | `national-id`, `tax-id` |
| `INVALID_PROVINCE_CODE` | Province code prefix is not between `01`-`24` or `30` | `national-id`, `tax-id` |
| `INVALID_THIRD_DIGIT` | Third digit is not within valid ranges for natural, public, or private entities | `national-id`, `tax-id` |
| `INVALID_ESTABLISHMENT` | Establishment branch code is `000` / `0000` (must be > 0) | `tax-id` |
| `INVALID_CHECKSUM` | Verification digit does not match algorithm validation | `national-id`, `tax-id` |


## License
MIT © [Codaminds](LICENSE).