import { describe, expect, it } from "vitest";
import { EcNationalIdValidator } from "../../../src/countries/EC/NationalIdValidator.js";
import { Identifier } from "../../../src/index.js";
import { loadVector } from "../../support/load-vectors.js";

describe("Ecuador - NationalIdValidator", () => {
	const validator = new EcNationalIdValidator();
	const vector = loadVector("EC", "national-id");

	describe("Test Vectors Validation", () => {
		it.each(vector.cases)(
			"$description (input: $input) -> expected: $expected",
			({ input, expected }) => {
				const result = validator.validate(input);

				expect(result.isValid).toBe(expected);
				expect(result.country).toBe("EC");
				expect(result.identifierType).toBe("national-id");

				if (!expected) {
					expect(result.errorCode).toBeDefined();
					expect(result.errorMessage).toBeDefined();
				}
			},
		);
	});

	describe("Facade Integration (Identifier)", () => {
		it("should validate correctly through the global registry facade", () => {
			expect(Identifier.isValid("EC", "national-id", "0926687856")).toBe(true);
			expect(Identifier.isValid("EC", "national-id", "0926687857")).toBe(false);

			const result = Identifier.validate("EC", "national-id", "0926687856");
			expect(result.isValid).toBe(true);
		});

		it("should throw an error for unsupported identifiers", () => {
			expect(() => {
				Identifier.validate("EC", "unsupported-id", "123");
			}).toThrow("Unsupported identifier validator: [EC:unsupported-id]");
		});
	});
});
