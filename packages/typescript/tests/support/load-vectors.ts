import { readFileSync, existsSync } from "node:fs";
import { resolve } from "node:path";

export interface TestCase {
	input: string;
	expected: boolean;
	description: string;
}

export interface TestVector {
	country: string;
	identifier: string;
	cases: TestCase[];
}

export function loadVector(country: string, identifier: string): TestVector {
	const envDir = process.env.TEST_VECTORS_DIR;

	// Soporta Docker (/app/test-vectors) y ejecución local desde packages/typescript
	const candidates = [
		envDir,
		resolve(process.cwd(), "test-vectors"),
		resolve(process.cwd(), "../../test-vectors"),
	].filter(Boolean) as string[];

	let foundPath: string | null = null;

	for (const base of candidates) {
		const candidatePath = resolve(
			base,
			country.toUpperCase(),
			`${identifier}.json`,
		);
		if (existsSync(candidatePath)) {
			foundPath = candidatePath;
			break;
		}
	}

	if (!foundPath) {
		throw new Error(`Test vector not found for [${country}/${identifier}]`);
	}

	const raw = readFileSync(foundPath, "utf-8");
	return JSON.parse(raw) as TestVector;
}
