# @codaminds/identifiers

> TypeScript/JavaScript implementation of **Codaminds Identifiers** — Multi-language, specification-driven national identifier validation library.

[![npm version](https://img.shields.io/npm/v/@codaminds/identifiers.svg)](https://www.npmjs.com/package/@codaminds/identifiers)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

---

## Notice

> ⚠️ **Main Development Repository:** This package is part of the Codaminds Identifiers multi-language monorepo. Please submit issues, discussions, and pull requests to the central repository at [Codaminds/identifiers](https://github.com/Codaminds/identifiers).

---

## Features

- 0️⃣ **Zero Dependencies**: Lightweight and fast.
- 🎯 **Full TypeScript Support**: Comprehensive type definitions for arguments, error codes, and validation results.
- 📐 **Specification-Driven**: Tested against strict language-agnostic test vector fixtures.
- 📦 **Dual Module Export**: Native support for ESM (`import`) and CommonJS (`require`).

---

## Installation

```bash
npm install @codaminds/identifiers
# or
pnpm add @codaminds/identifiers
# or
yarn add @codaminds/identifiers
```

##  Usage

```ts
import { Identifier } from '@codaminds/identifiers';

// Quick checks
const isValidId = Identifier.isValid('EC', 'national-id', '0926687856');
const isValidRuc = Identifier.isValid('EC', 'tax-id', '0926687856001');

// Detailed validation
const result = Identifier.validate('EC', 'tax-id', '0926687856000');

if (!result.isValid) {
    console.log(result.errorCode);    // 'INVALID_ESTABLISHMENT'
    console.log(result.errorMessage); // 'Establishment code must be greater than zero'
}
```
---

## Supported Identifiers

| Country       | Identifier | Code | Support |
|:--------------| :--- | :--- |:---------:|
| 🇪🇨  Ecuador | Cédula de Identidad | `national-id` |     ✅     |
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