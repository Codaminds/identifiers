import type { IdentifierValidator, ValidationResult } from "../../types.js";
import { EcNationalIdValidator } from "./NationalIdValidator.js";

export class EcRucValidator implements IdentifierValidator {
	public readonly countryCode = "EC";
	public readonly identifierType = "tax-id";

	private static readonly THIRD_DIGIT_PUBLIC = 6;
	private static readonly THIRD_DIGIT_PRIVATE = 9;

	constructor(
		private readonly nationalIdValidator: IdentifierValidator = new EcNationalIdValidator(),
	) {}

	public validate(value: string): ValidationResult {
		const sanitized = value.trim().replace(/[-\s]/g, "");

		if (!/^\d+$/.test(sanitized)) {
			return this.failure(
				"INVALID_FORMAT",
				"Value must contain only numeric digits",
			);
		}

		if (sanitized.length !== 13) {
			return this.failure("INVALID_LENGTH", "Value must be exactly 13 digits");
		}

		const province = parseInt(sanitized.slice(0, 2), 10);
		if (!((province >= 1 && province <= 24) || province === 30)) {
			return this.failure(
				"INVALID_PROVINCE_CODE",
				"Province code must be between 01 and 24, or 30",
			);
		}

		const thirdDigit = parseInt(sanitized[2], 10);

		// 1. Persona Natural: Tercer dígito de 0 a 5
		if (thirdDigit < EcRucValidator.THIRD_DIGIT_PUBLIC) {
			return this.validateNaturalPerson(sanitized);
		}

		// 2. Entidad Pública: Tercer dígito igual a 6
		if (thirdDigit === EcRucValidator.THIRD_DIGIT_PUBLIC) {
			return this.validatePublicEntity(sanitized);
		}

		// 3. Sociedad Privada / Extranjero sin cédula: Tercer dígito igual a 9
		if (thirdDigit === EcRucValidator.THIRD_DIGIT_PRIVATE) {
			return this.validatePrivateCompany(sanitized);
		}

		return this.failure(
			"INVALID_THIRD_DIGIT",
			`Third digit ${thirdDigit} is invalid for Ecuadorian RUC`,
		);
	}

	private validateNaturalPerson(value: string): ValidationResult {
		const establishment = value.slice(10, 13);
		if (parseInt(establishment, 10) === 0) {
			return this.failure(
				"INVALID_ESTABLISHMENT",
				"Establishment code must be greater than zero",
			);
		}

		const nationalId = value.slice(0, 10);
		const result = this.nationalIdValidator.validate(nationalId);

		if (!result.isValid) {
			return this.failure(
				result.errorCode ?? "INVALID_CHECKSUM",
				result.errorMessage ?? "Verification digit does not match algorithm",
			);
		}

		return this.success();
	}

	private validatePrivateCompany(value: string): ValidationResult {
		const establishment = value.slice(10, 13);
		if (parseInt(establishment, 10) === 0) {
			return this.failure(
				"INVALID_ESTABLISHMENT",
				"Establishment code must be greater than zero",
			);
		}

		return this.success();
	}

	private validatePublicEntity(value: string): ValidationResult {
		const establishment = value.slice(9, 13);
		if (parseInt(establishment, 10) === 0) {
			return this.failure(
				"INVALID_ESTABLISHMENT",
				"Establishment code must be greater than zero",
			);
		}

		return this.success();
	}

	private success(): ValidationResult {
		return {
			isValid: true,
			country: this.countryCode,
			identifierType: this.identifierType,
		};
	}

	private failure(errorCode: string, errorMessage: string): ValidationResult {
		return {
			isValid: false,
			country: this.countryCode,
			identifierType: this.identifierType,
			errorCode,
			errorMessage,
		};
	}
}
