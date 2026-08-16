import type { IdentifierValidator, ValidationResult } from "../../types.js";

export class EcNationalIdValidator implements IdentifierValidator {
    public readonly countryCode = "EC";
    public readonly identifierType = "national-id";
    private static readonly COEFFICIENTS = [2, 1, 2, 1, 2, 1, 2, 1, 2] as const;

    public validate(value: string): ValidationResult {
        const sanitized = value.trim().replace(/[-\s]/g, "");

        if (!/^\d{10}$/.test(sanitized)) {
            return {
                isValid: false,
                country: this.countryCode,
                identifierType: this.identifierType,
                errorCode: "INVALID_FORMAT",
                errorMessage: "Identifier must be exactly 10 numeric digits",
            };
        }

        const province = parseInt(sanitized.slice(0, 2), 10);
        if (!((province >= 1 && province <= 24) || province === 30)) {
            return {
                isValid: false,
                country: this.countryCode,
                identifierType: this.identifierType,
                errorCode: "INVALID_PROVINCE_CODE",
                errorMessage: "Province code must be between 01-24 or 30",
            };
        }

        const thirdDigit = parseInt(sanitized[2], 10);
        if (thirdDigit >= 6) {
            return {
                isValid: false,
                country: this.countryCode,
                identifierType: this.identifierType,
                errorCode: "INVALID_THIRD_DIGIT",
                errorMessage: "Third digit must be less than 6 for natural persons",
            };
        }

        let sum = 0;
        for (let i = 0; i < 9; i++) {
            const product = parseInt(sanitized[i], 10) * EcNationalIdValidator.COEFFICIENTS[i];
            sum += product >= 10 ? product - 9 : product;
        }

        const verifier = (10 - (sum % 10)) % 10;
        if (verifier !== parseInt(sanitized[9], 10)) {
            return {
                isValid: false,
                country: this.countryCode,
                identifierType: this.identifierType,
                errorCode: "INVALID_CHECKSUM",
                errorMessage: "Verification digit does not match Luhn mod 10 algorithm",
            };
        }

        return {
            isValid: true,
            country: this.countryCode,
            identifierType: this.identifierType,
        };
    }
}