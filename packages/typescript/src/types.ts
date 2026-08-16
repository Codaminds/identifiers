export interface ValidationResult {
	readonly isValid: boolean;
	readonly country: string;
	readonly identifierType: string;
	readonly errorCode?: string;
	readonly errorMessage?: string;
}

export interface IdentifierValidator {
	readonly countryCode: string;
	readonly identifierType: string;
	validate(value: string): ValidationResult;
}
