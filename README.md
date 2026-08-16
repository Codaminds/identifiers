# Identifiers

> Multi-language, spec-driven national identifier validation library maintained by **Codaminds**.

[![CI Suite](https://github.com/codaminds/identifiers/actions/workflows/ci.yml/badge.svg)](https://github.com/codaminds/identifiers/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

---

## Overview

Validating official documents (National IDs, Tax Numbers, Social Security codes) across different countries often leads to fragmented codebases and subtle algorithm drift.

**Identifiers** solves this by providing:
- **Language-Agnostic Specifications:** Algorithms documented under `spec/`.
- **Shared Test Vectors:** A single source of truth in `test-vectors/` tested against all runtimes.
- **Consistent Public API:** Identical interface and error handling in PHP and TypeScript.

---

## Supported Identifiers

| Country | Identifier | Code | PHP Support | TS Support |
| :--- | :--- | :--- | :---: | :---: |
| 🇪🇨 Ecuador | Cédula de Identidad | `national-id` | ✅ | ✅ |

---

## Installation

### PHP (8.1+)
```bash
composer require codaminds/identifiers
```
### TypeScript / Node.js (18+)
```bash
npm install @codaminds/identifiers
```

## Quick Usage

#### PHP
```php
use Codaminds\Identifiers\Identifier;

// Boolean check
$isValid = Identifier::isValid('EC', 'national-id', '0926687856'); // true

// Detailed validation result
$result = Identifier::validate('EC', 'national-id', '0926687857');
if (!$result->isValid) {
    echo $result->errorCode;    // 'INVALID_CHECKSUM'
    echo $result->errorMessage; // 'Verification digit does not match Luhn mod 10 algorithm'
}
```

#### TypeScript
```ts
import { Identifier } from '@codaminds/identifiers';

// Boolean check
const isValid = Identifier.isValid('EC', 'national-id', '0926687856'); // true

// Detailed validation result
const result = Identifier.validate('EC', 'national-id', '0926687857');
if (!result.isValid) {
  console.log(result.errorCode);    // 'INVALID_CHECKSUM'
  console.log(result.errorMessage); // 'Verification digit does not match Luhn mod 10 algorithm'
}
```

## Repository Structure

```
identifiers/
├── spec/             # Formal algorithms & specs per country
├── test-vectors/     # Language-agnostic test JSON files
├── packages/
│   ├── php/          # Packagist package (codaminds/identifiers)
│   └── typescript/   # NPM package (@codaminds/identifiers)
└── .github/          # CI/CD & automated release pipelines
```



## License
This project is licensed under the [MIT License](LICENSE).