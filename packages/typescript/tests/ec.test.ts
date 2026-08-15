import { describe, it, expect } from "vitest";
import { readFileSync } from "node:fs";
import { resolve } from "node:path";
import { validateEcNationalId } from "../src/index.js";

const vectorPath = resolve(__dirname, "../test-vectors/EC/national-id.json");
const testData = JSON.parse(readFileSync(vectorPath, "utf-8"));

describe("Ecuador National ID Validator", () => {
    testData.cases.forEach((tc: { input: string; expected: boolean; description: string }) => {
        it(tc.description, () => {
            const res = validateEcNationalId(tc.input);
            expect(res.isValid).toBe(tc.expected);
        });
    });
});