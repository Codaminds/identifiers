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

#### Simple Boolean Validation
```ts
import { Identifier } from '@codaminds/identifiers';

const result = Identifier.validate('EC', 'national-id', '0926687857');

if (result.isValid) {
    console.log('Valid document!');
} else {
    console.error('Validation failed:', {
        code: result.errorCode,       // e.g., 'INVALID_CHECKSUM'
        message: result.errorMessage, // e.g., 'Verification digit does not match algorithm'
    });
}
```
---

## Supported Identifiers

| Country       | Identifier | Code | TS Support |
|:--------------| :--- | :--- |:---------:|
| 🇪🇨  Ecuador | Cédula de Identidad | `national-id` |     ✅     |

---
## Error Codes

When `validate()`  fails, `errorCode` returns one of the standardized error keys:

- `INVALID_FORMAT`: Value contains non-allowed characters or structure.

- `INVALID_LENGTH`: Character count does not match the specification.

- `INVALID_CHECKSUM`: Modulo verification algorithm failed.

- `INVALID_PROVINCE_CODE`: Prefix does not belong to a valid region.

- `UNSUPPORTED_IDENTIFIER`: Country or document type is not yet registered.


## License
MIT © [Codaminds](LICENSE).