# Cédula de Identidad - Ecuador (EC)

## Estructura
- **Longitud:** 10 dígitos numéricos.
- **Dígitos 1-2:** Código de provincia (01 a 24, o 30 para residentes en el exterior).
- **Dígito 3:** Tipo de documento (debe ser menor a 6 para personas naturales).
- **Dígitos 4-9:** Número secuencial.
- **Dígito 10:** Dígito verificador (Módulo 10).

## Algoritmo de Verificación (Módulo 10)
1. Coeficientes: `[2, 1, 2, 1, 2, 1, 2, 1, 2]`.
2. Multiplicar los primeros 9 dígitos por su respectivo coeficiente.
3. Si el resultado de una multiplicación es $\ge 10$, restar 9.
4. Sumar todos los resultados.
5. Calcular la decena superior inmediata: `decena_superior = Math.ceil(suma / 10) * 10`.
6. `digito_esperado = (decena_superior - suma) % 10`.
7. El identificador es válido si `digito_esperado === digito_10`.