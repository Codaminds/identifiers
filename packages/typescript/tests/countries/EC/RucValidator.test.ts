import { describe, expect, it } from "vitest";
import { EcNationalIdValidator } from "../../../src/countries/EC/NationalIdValidator.js";
import { EcRucValidator } from "../../../src/countries/EC/RucValidator.js";
import { Identifier } from "../../../src/index.js";
import { loadVector } from "../../support/load-vectors.js";

describe("Ecuador - RucValidator", () => {
	const validator = new EcRucValidator(new EcNationalIdValidator());
	const vector = loadVector("EC", "tax-id");

	describe("Test Vectors Validation", () => {
		it.each(vector.cases)(
			"$description (input: $input) -> expected: $expected",
			({ input, expected }) => {
				const result = validator.validate(input);

				expect(result.isValid).toBe(expected);
				expect(result.country).toBe("EC");
				expect(result.identifierType).toBe("tax-id");

				if (!expected) {
					expect(result.errorCode).toBeDefined();
					expect(result.errorMessage).toBeDefined();
				}
			},
		);
	});

	describe("Facade Integration (Identifier)", () => {
		it("should validate correctly through the global registry facade", () => {
			expect(Identifier.isValid("EC", "tax-id", "0926687856001")).toBe(true);
			expect(Identifier.isValid("EC", "tax-id", "0926687856000")).toBe(false);

			const result = Identifier.validate("EC", "tax-id", "0926687856001");
			expect(result.isValid).toBe(true);
			expect(result.country).toBe("EC");
			expect(result.identifierType).toBe("tax-id");
		});
	});
});
