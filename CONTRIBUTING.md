# Contributing to Identifiers

Thank you for your interest in contributing to **Identifiers by Codaminds**. We follow a specification-first approach to ensure 100% behavioral parity across all supported programming languages.

---

## 3-Step Contribution Workflow for New Identifiers

When adding support for a new country or identifier, follow these mandatory steps in order:

### 1. Write the Specification (`spec/`)
Create a markdown file describing the format, checksum rules, and constraints:
- Path: `spec/countries/{COUNTRY_CODE}/{identifier-name}.md` (e.g., `spec/countries/PE/dni.md`)

### 2. Define Shared Test Vectors (`test-vectors/`)
Create a JSON file containing comprehensive test cases (valid inputs, edge cases, invalid checksums):
- Path: `test-vectors/{COUNTRY_CODE}/{identifier-name}.json`

```json
{
  "country": "PE",
  "identifier": "dni",
  "cases": [
    {
      "input": "12345678",
      "expected": true,
      "description": "Valid standard DNI"
    }
  ]
}
```
### 3. Implement Across Packages (`packages/`)
Implement the validator in both PHP and TypeScript using the shared test vectors:

- PHP: `packages/php/src/Countries/{COUNTRY}/{ValidatorName}.php`

- TypeScript:`packages/typescript/src/countries/{COUNTRY}/{ValidatorName}.ts`

# Development Setup
We use Docker Compose to provide an isolated development environment without needing local PHP or Node runtimes:

```
# Build and start development containers
docker compose up -d --build

# Run tests
docker compose exec php-app composer test
docker compose exec node-app npm test

# Linting & Formatting
docker compose exec php-app composer lint
docker compose exec node-app npm run lint
```

# Commit & PR Guidelines
- Use conventional commits: `feat(EC): add ruc natural person validator, fix(core): resolve registry lookup bug.`

- Ensure all CI tests pass across all PHP and Node versions before requesting review.

- Do not add language-specific business logic that deviates from the spec/ document.
