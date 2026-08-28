# INSTRUCCIONES LUCID — DIAGRAMAS DE ACTIVIDAD — ROL: Programador — HAMBURGUER COPIWAY

Documento independiente. Úsalo en un archivo/documento de Lucid **aparte** de los demás roles.

RF incluidos en este documento: RF-25 (1 diagramas).

---

## CÓMO USAR ESTE DOCUMENTO

1. Pega primero el **PROMPT MAESTRO** (sección A) en un documento NUEVO de Lucid para fijar el estilo visual exacto, la orientación VERTICAL de página, y la regla de NO hacer preguntas.
2. Pega luego, en orden, cada una de las 1 instrucciones (sección B). Cada una crea, en una página nueva de ese mismo documento, un DIAGRAMA DE ACTIVIDADES (UML) completo, en formato vertical, sin que la IA deba preguntarte nada.
3. Cada instrucción trae el bloque **"Decisión"** con dos ramas explícitas — "Si la respuesta es 'Sí'" y "Si la respuesta es 'No'" — cada una con su actividad exacta o su continuación exacta. Esto es justamente lo que antes generaba la pregunta de aclaración: ahora ya no hace falta preguntar nada, todo está resuelto de antemano.
4. El texto entre paréntesis en los pasos es solo referencia tuya; NO se dibuja en el diagrama.
5. El texto entre corchetes `[Módulo: ...]` en el encabezado es solo referencia tuya; no se dibuja en el diagrama.

---

## A. PROMPT MAESTRO (pegar una sola vez, primero, en este documento)

```
Vamos a crear, en un solo documento de Lucidchart, 1 DIAGRAMAS DE ACTIVIDAD (UML) — uno por página, uno por cada Requerimiento Funcional (RF) del rol "Programador" — para el sistema "Hamburguer Copiway". Cada página debe contener un diagrama de actividades completo (con nodo de inicio, actividades secuenciales, un punto de decisión y nodo de fin), no un simple listado ni un diagrama de otro tipo. Todas las páginas deben tener EXACTAMENTE el mismo estilo visual, sin variaciones entre ellas. Sigue esta plantilla al pie de la letra.

REGLA CRÍTICA — NO HAGAS PREGUNTAS DE ACLARACIÓN (léelo primero):
- Cada instrucción que te voy a enviar, una por una, ya trae TODA la información necesaria: los pasos exactos, la pregunta exacta del rombo de decisión, y qué actividad exacta va en CADA una de las dos ramas ("Sí" y "No"), incluyendo a qué carril pertenece cada actividad.
- NO me preguntes "qué debe pasar cuando el rombo responde Sí/No", ni me muestres opciones para elegir, ni pidas confirmación de ningún tipo antes de dibujar. Ejecuta la instrucción tal cual está escrita, de una sola vez.
- Cada instrucción individual trae, dentro de su bloque "Decisión", una sección "Si la respuesta es 'Sí':" y otra "Si la respuesta es 'No':" con el contenido EXACTO de cada rama, más una "REGLA FIJA" que resume cuál rama lleva a la actividad nueva y cuál rama continúa con los pasos restantes. Sigue esas dos secciones literalmente, sin interpretarlas, sin invertirlas y sin autocompletar con contenido que no esté ahí escrito.
- Si en algún caso extremo un dato pareciera faltar, NO te detengas a preguntar: aplica por defecto la "REGLA FIJA" de esa misma instrucción y continúa.

ORIENTACIÓN DE PÁGINA (OBLIGATORIO):
- Cada página del documento debe configurarse en orientación VERTICAL (formato retrato): más ALTA que ANCHA. Usa un lienzo angosto en el ancho (aprox. 850 px de ancho) y tan alto como sea necesario para que quepan todos los pasos (puede llegar a 1400, 1800, 2200 px de alto o más, según la cantidad de pasos de cada RF). NUNCA generes la página en orientación horizontal/paisaje ni más ancha que alta.
- Si el diagrama tiene muchos pasos y no cabe en una vista cómoda, la solución es ALARGAR EL ALTO de la página (crecer hacia abajo), JAMÁS ensanchar el lienzo ni distribuir los pasos en horizontal.
- Los dos carriles (swimlanes) se dibujan como dos COLUMNAS VERTICALES angostas, una al lado de la otra (izquierda / derecha), separadas por una línea vertical delgada. Dentro de cada columna, todas las actividades se apilan una debajo de otra, en una sola fila de símbolos por carril (no las repartas en varias columnas dentro del mismo carril).
- Todo el flujo del diagrama (Inicio → pasos → decisión → Fin) avanza de ARRIBA HACIA ABAJO. Las flechas entre pasos consecutivos de un mismo carril deben ser verticales (hacia abajo), no diagonales ni horizontales extendidas. Solo se permiten segmentos horizontales cortos cuando el flujo cruza de un carril al otro o cuando el rombo se conecta a la actividad nueva de la rama "Sí".
- No apliques un "layout automático" tipo árbol horizontal ni organices los símbolos en una sola fila ancha: respeta el crecimiento vertical descrito arriba en TODAS las páginas.

ESTRUCTURA DE CADA PÁGINA:
- Rótulo de título en la esquina superior izquierda: rectángulo pequeño de fondo gris oscuro/negro con texto blanco, formato "<Código> <Nombre del RF>".
- Dos carriles (swimlanes) en formato tabla simple, SIN relleno de color en el encabezado ni en el fondo del carril: solo una línea vertical delgada que separa las columnas, y el nombre del actor como texto plano centrado arriba de cada columna (izquierda: "Programador"; derecha: "Sistema"). No uses contenedores de color tipo "Añadir título" para los carriles.

FORMAS Y COLORES EXACTOS (replicar siempre igual, notación UML de Diagrama de Actividades):
- Nodo INICIO: círculo, relleno AZUL CLARO (#CFE2FF), borde azul oscuro, texto "Inicio".
- Nodo FIN: círculo, relleno AZUL CLARO (#CFE2FF), borde azul oscuro, texto "Fin". Debe existir un solo nodo de Inicio y un solo nodo de Fin por página (todas las ramas terminan en ese mismo Fin).
- Actividades (pasos del proceso): rectángulo de esquinas redondeadas, relleno AZUL CLARO (#CFE2FF), borde azul oscuro, texto negro centrado. Ubicadas en el carril del actor que las ejecuta.
- Nodo de DECISIÓN: rombo, relleno ROSA/MAGENTA (#FF4FD8), borde oscuro, con la pregunta exacta que te doy en cada instrucción.
- Nota de detalles: rectángulo AMARILLO (#FFF59D), SIN BORDE (sin línea de contorno, solo el relleno), ubicada en la esquina superior derecha del carril "Sistema".
- Flechas: líneas negras delgadas SIN texto, excepto las dos que salen del rombo, etiquetadas únicamente "Sí" y "No".

TEXTO DENTRO DE LAS FORMAS:
- Usa EXACTAMENTE el texto corto que te doy en cada paso y en cada rama de la decisión (ya vienen resumidos, 4-9 palabras). No los alargues, no agregues la explicación entre paréntesis: esa es solo tu referencia de significado, nunca se escribe dentro del símbolo.
- No agregues pasos, actores, rótulos, colores ni cajas que no estén explícitamente en la instrucción de cada página. No completes ni inventes contenido adicional por tu cuenta (no autocompletes): si un dato no aparece en la instrucción, no lo agregues.

FLUJO DEL DIAGRAMA DE ACTIVIDADES (aplica en cada una de las 1 páginas):
- Inicio → pasos de la Secuencia Normal en orden, cada uno en el carril correspondiente (Actor o Sistema) → rombo de decisión en el punto de validación (ubicado justo después del último paso ya dibujado hasta ese momento, según se indique en cada instrucción).
- Cada instrucción individual te dice EXACTAMENTE qué dibujar en la rama "Sí" y qué dibujar en la rama "No" — sigue eso literalmente, no la inviertas y no la completes por tu cuenta.
- Ambas ramas siempre terminan en el mismo nodo Fin.

REGLAS GENERALES:
- Cada página es un DIAGRAMA DE ACTIVIDADES (UML), no un flujograma libre, ni un diagrama de casos de uso, ni un mockup.
- No agregues pasos, actores, rótulos ni colores distintos a los aquí definidos.
- Título de cada página (pestaña): "<Código> - <Nombre del RF>".
- Aplica este mismo estilo, sin excepción, en las 1 páginas, incluida la orientación vertical y la regla de no hacer preguntas.

Confirma que entendiste el estilo, especialmente: (1) la orientación VERTICAL de página, y (2) que NO debes hacerme preguntas de aclaración porque cada instrucción trae todo completo. Luego te enviaré, una por una, las 1 instrucciones con el contenido específico de cada RF del rol "Programador", cada una pidiéndote crear su Diagrama de Actividades.
```

---

## B. INSTRUCCIONES INDIVIDUALES (1 páginas — Rol: Programador)

### Página 1 — RF-25: Creación Interna de la Cuenta Maestra del Administrador  `[Módulo: Programador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-25 - Creación Interna de la Cuenta Maestra del Administrador" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Programador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Programador] Ejecuta el script/seeder de inicialización del sistema  (El programador ejecuta el script/seeder de inicialización del sistema.)
  2. [Sistema] Valida que no exista previamente una cuenta  (El sistema valida que no exista previamente una cuenta con rol Administrador.)
  3. [Sistema] Encripta la contraseña  (El sistema encripta la contraseña y crea el registro del Administrador directamente en la base de datos.)

Decisión (símbolo): ¿Ya existe una cuenta de Administrador registrada?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza la creación de una segunda cuenta"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - No existe ningún formulario web para esta acción; se ejecuta exclusivamente por consola/BD.
  - Es un procedimiento de única vez durante la puesta en marcha del sistema.
```
