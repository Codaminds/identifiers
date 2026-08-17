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

### Sociedades Privadas y Extranjeros sin Cédula (`3er dígito = 9`)

* **Longitud**: Exactamente 13 dígitos numéricos.
* **Código de Provincia**: Los dígitos 1 y 2 deben estar en el rango de `01` a `24`, o ser `30` (jurisdicción especial / exterior).
* **Tercer Dígito**: Debe ser estrictamente igual a `9`.
* **Código de Establecimiento**: Los dígitos 11 al 13 deben ser numéricos y estrictamente mayores a cero (`001` a `999`, nunca `000`).
* **Dígito Verificador**: De acuerdo con las directrices oficiales del SRI, este tipo de RUC no cuenta con un algoritmo específico obligatorio de dígito verificador, por lo que no se aplica validación por checksum para evitar falsos rechazos en documentos vigentes.

### Entidad Pública

* **Longitud**: Exactamente 13 dígitos numéricos.
* **Código de Provincia**: Los dígitos 1 y 2 deben estar en el rango de `01` a `24`, o ser `30` (jurisdicción especial / exterior).
* **Tercer Dígito**: Debe ser estrictamente igual a `6`.
* **Código de Establecimiento**: Los dígitos 10 al 13 deben ser numéricos y estrictamente mayores a cero (`0001` a `9999`, nunca `0000`).
* **Dígito Verificador**: De acuerdo con las directrices oficiales del SRI, este tipo de RUC no cuenta con un algoritmo específico obligatorio de dígito verificador, por lo que no se aplica validación por checksum para evitar falsos rechazos en documentos vigentes.

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

De acuerdo con las directrices oficiales del SRI, este tipo de RUC no cuenta con un algoritmo específico obligatorio de dígito verificador, por lo que en esta especificación **no se aplica validación por checksum** para evitar falsos rechazos en documentos vigentes.

### Entidad Pública

Para los RUC cuyo tercer dígito es `6`, el dígito verificador se encuentra en la **posición 9**.

De acuerdo con las directrices oficiales del SRI, este tipo de RUC no cuenta con un algoritmo específico obligatorio de dígito verificador, por lo que en esta especificación **no se aplica validación por checksum** para evitar falsos rechazos en documentos vigentes.

## Reglas de Validación

Un RUC ecuatoriano se considera válido cuando:

1. Contiene exactamente **13 dígitos numéricos**.
2. Los dos primeros dígitos corresponden a una provincia válida (`01`-`24` o `30`).
3. El tercer dígito determina correctamente el tipo de contribuyente.
4. Para personas naturales (tercer dígito `0` a `5`), el dígito verificador coincide con el algoritmo Módulo 10 de cédula.
5. El código de establecimiento no es `000` para personas naturales/sociedades privadas ni `0000` para entidades públicas.
