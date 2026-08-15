export interface ValidationResult {
    isValid: boolean;
    country: string;
    identifier: string;
    error?: string;
}

export function validateEcNationalId(value: string): ValidationResult {
    const cleanValue = value.trim().replace(/[-\s]/g, "");
    const result: ValidationResult = {
        isValid: false,
        country: "EC",
        identifier: "national-id"
    };

    if (!/^\d{10}$/.test(cleanValue)) {
        result.error = "INVALID_FORMAT_OR_LENGTH";
        return result;
    }

    const province = parseInt(cleanValue.slice(0, 2), 10);
    if (!((province >= 1 && province <= 24) || province === 30)) {
        result.error = "INVALID_PROVINCE";
        return result;
    }

    const thirdDigit = parseInt(cleanValue[2], 10);
    if (thirdDigit >= 6) {
        result.error = "INVALID_THIRD_DIGIT";
        return result;
    }

    const digits = cleanValue.split("").map(Number);
    const coefficients = [2, 1, 2, 1, 2, 1, 2, 1, 2];

    let sum = 0;
    for (let i = 0; i < 9; i++) {
        let product = digits[i] * coefficients[i];
        if (product >= 10) product -= 9;
        sum += product;
    }

    const checkDigit = (10 - (sum % 10)) % 10;
    if (checkDigit !== digits[9]) {
        result.error = "INVALID_CHECKSUM";
        return result;
    }

    result.isValid = true;
    return result;
}