import type { IdentifierValidator, ValidationResult } from "./types.js";
import { EcNationalIdValidator } from "./countries/EC/NationalIdValidator.js";
import { EcRucValidator } from "./countries/EC/RucValidator.js";

export * from "./types.js";
export { EcNationalIdValidator, EcRucValidator };

class IdentifierRegistry {
    private validators = new Map<string, IdentifierValidator>();

    constructor() {
        const ecNationalId = new EcNationalIdValidator();
        this.register(ecNationalId);
        this.register(new EcRucValidator(ecNationalId));
    }

    public register(validator: IdentifierValidator): void {
        const key = `${validator.countryCode.toUpperCase()}:${validator.identifierType.toLowerCase()}`;
        this.validators.set(key, validator);
    }

    public validate(country: string, type: string, value: string): ValidationResult {
        const key = `${country.toUpperCase()}:${type.toLowerCase()}`;
        const validator = this.validators.get(key);

        if (!validator) {
            throw new Error(`Unsupported identifier validator: [${key}]`);
        }

        return validator.validate(value);
    }

    public isValid(country: string, type: string, value: string): boolean {
        return this.validate(country, type, value).isValid;
    }
}

export const Identifier = new IdentifierRegistry();