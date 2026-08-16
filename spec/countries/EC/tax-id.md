# RUC - Ecuador (EC)

## Estructura

* **Longitud:** 13 dígitos numéricos.
* **Dígitos 1-2:** Código de provincia (`01` a `24`, o `30` para residentes en el exterior).
* **Dígito 3:** Determina el tipo de contribuyente:

    * `0` a `5`: Persona natural.
    * `6`: Entidad pública.
    * `9`: Sociedad privada o extranjero sin cédula.
* **Dígitos restantes:** Dependen del tipo de RUC y contienen el dígito verificador y el código de establecimiento.

### Persona Natural

* **Dígito 3:** Menor a `6` (`0` a `5`).
* **Dígitos 1-10:** Corresponden a una **Cédula de Identidad válida**, verificada mediante el algoritmo Módulo 10.
* **Dígitos 11-13:** Código de establecimiento.

    * Debe ser diferente de `000`.
    * Habitualmente corresponde a valores como `001`, `002`, etc.

### Sociedad Privada / Extranjero sin cédula

* **Dígito 3:** Exactamente `9`.
* **Dígitos 1-9:** Número base del contribuyente.
* **Dígito 10:** Dígito verificador, calculado mediante el algoritmo Módulo 11.
* **Dígitos 11-13:** Código de establecimiento.

    * Debe ser diferente de `000`.
    * Habitualmente corresponde a valores como `001`, `002`, etc.

### Entidad Pública

* **Dígito 3:** Exactamente `6`.
* **Dígitos 1-8:** Número base de la entidad.
* **Dígito 9:** Dígito verificador, calculado mediante el algoritmo Módulo 11.
* **Dígitos 10-13:** Código de establecimiento.

    * Debe ser diferente de `0000`.
    * Habitualmente corresponde a valores como `0001`, `0002`, etc.

## Algoritmo de Verificación

### Persona Natural

Para los RUC de persona natural, los primeros 10 dígitos deben constituir una **Cédula de Identidad válida**.

Se aplica el algoritmo **Módulo 10**:

1. Tomar los primeros 9 dígitos.
2. Utilizar los coeficientes `[2, 1, 2, 1, 2, 1, 2, 1, 2]`.
3. Multiplicar cada dígito por su coeficiente correspondiente.
4. Si el resultado es `>= 10`, restar `9`.
5. Sumar todos los resultados.
6. Calcular la decena superior inmediata:
   `decena_superior = Math.ceil(suma / 10) * 10`.
7. Calcular el dígito esperado:
   `digito_esperado = (decena_superior - suma) % 10`.
8. El RUC es válido si el dígito esperado coincide con el **décimo dígito**.

### Sociedad Privada / Extranjero sin cédula

Para los RUC cuyo tercer dígito es `9`, el dígito verificador se encuentra en la **posición 10**.

Se aplica el algoritmo **Módulo 11**:

1. Tomar los primeros 9 dígitos.
2. Utilizar los coeficientes `[4, 3, 2, 7, 6, 5, 4, 3, 2]`.
3. Multiplicar cada dígito por su coeficiente correspondiente.
4. Sumar los resultados.
5. Calcular el residuo de la división entre `11`.
6. Calcular el dígito verificador:

    * Si el residuo es `0`, el dígito esperado es `0`.
    * Si el residuo es `1`, el RUC es inválido.
    * En los demás casos, el dígito esperado es `11 - residuo`.
7. El RUC es válido si el dígito esperado coincide con el **décimo dígito**.

### Entidad Pública

Para los RUC cuyo tercer dígito es `6`, el dígito verificador se encuentra en la **posición 9**.

Se aplica el algoritmo **Módulo 11**:

1. Tomar los primeros 8 dígitos.
2. Utilizar los coeficientes `[3, 2, 7, 6, 5, 4, 3, 2]`.
3. Multiplicar cada dígito por su coeficiente correspondiente.
4. Sumar los resultados.
5. Calcular el residuo de la división entre `11`.
6. Calcular el dígito verificador:

    * Si el residuo es `0`, el dígito esperado es `0`.
    * Si el residuo es `1`, el RUC es inválido.
    * En los demás casos, el dígito esperado es `11 - residuo`.
7. El RUC es válido si el dígito esperado coincide con el **noveno dígito**.

## Reglas de Validación

Un RUC ecuatoriano se considera válido cuando:

1. Contiene exactamente **13 dígitos numéricos**.
2. Los dos primeros dígitos corresponden a una provincia válida (`01`-`24` o `30`).
3. El tercer dígito determina correctamente el tipo de contribuyente.
4. El dígito verificador coincide con el algoritmo correspondiente.
5. El código de establecimiento no es `000` para personas naturales/sociedades privadas ni `0000` para entidades públicas.
