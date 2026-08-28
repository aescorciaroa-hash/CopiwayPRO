# INSTRUCCIONES LUCID — DIAGRAMAS DE ACTIVIDAD — ROL: Ayudante de Cocina — HAMBURGUER COPIWAY

Documento independiente. Úsalo en un archivo/documento de Lucid **aparte** de los demás roles.

RF incluidos en este documento: RF-54 a RF-63 (10 diagramas).

---

## CÓMO USAR ESTE DOCUMENTO

1. Pega primero el **PROMPT MAESTRO** (sección A) en un documento NUEVO de Lucid para fijar el estilo visual exacto, la orientación VERTICAL de página, y la regla de NO hacer preguntas.
2. Pega luego, en orden, cada una de las 10 instrucciones (sección B). Cada una crea, en una página nueva de ese mismo documento, un DIAGRAMA DE ACTIVIDADES (UML) completo, en formato vertical, sin que la IA deba preguntarte nada.
3. Cada instrucción trae el bloque **"Decisión"** con dos ramas explícitas — "Si la respuesta es 'Sí'" y "Si la respuesta es 'No'" — cada una con su actividad exacta o su continuación exacta. Esto es justamente lo que antes generaba la pregunta de aclaración: ahora ya no hace falta preguntar nada, todo está resuelto de antemano.
4. El texto entre paréntesis en los pasos es solo referencia tuya; NO se dibuja en el diagrama.
5. El texto entre corchetes `[Módulo: ...]` en el encabezado es solo referencia tuya; no se dibuja en el diagrama.

---

## A. PROMPT MAESTRO (pegar una sola vez, primero, en este documento)

```
Vamos a crear, en un solo documento de Lucidchart, 10 DIAGRAMAS DE ACTIVIDAD (UML) — uno por página, uno por cada Requerimiento Funcional (RF) del rol "Ayudante de Cocina" — para el sistema "Hamburguer Copiway". Cada página debe contener un diagrama de actividades completo (con nodo de inicio, actividades secuenciales, un punto de decisión y nodo de fin), no un simple listado ni un diagrama de otro tipo. Todas las páginas deben tener EXACTAMENTE el mismo estilo visual, sin variaciones entre ellas. Sigue esta plantilla al pie de la letra.

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
- Dos carriles (swimlanes) en formato tabla simple, SIN relleno de color en el encabezado ni en el fondo del carril: solo una línea vertical delgada que separa las columnas, y el nombre del actor como texto plano centrado arriba de cada columna (izquierda: "Ayudante de Cocina"; derecha: "Sistema"). No uses contenedores de color tipo "Añadir título" para los carriles.

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

FLUJO DEL DIAGRAMA DE ACTIVIDADES (aplica en cada una de las 10 páginas):
- Inicio → pasos de la Secuencia Normal en orden, cada uno en el carril correspondiente (Actor o Sistema) → rombo de decisión en el punto de validación (ubicado justo después del último paso ya dibujado hasta ese momento, según se indique en cada instrucción).
- Cada instrucción individual te dice EXACTAMENTE qué dibujar en la rama "Sí" y qué dibujar en la rama "No" — sigue eso literalmente, no la inviertas y no la completes por tu cuenta.
- Ambas ramas siempre terminan en el mismo nodo Fin.

REGLAS GENERALES:
- Cada página es un DIAGRAMA DE ACTIVIDADES (UML), no un flujograma libre, ni un diagrama de casos de uso, ni un mockup.
- No agregues pasos, actores, rótulos ni colores distintos a los aquí definidos.
- Título de cada página (pestaña): "<Código> - <Nombre del RF>".
- Aplica este mismo estilo, sin excepción, en las 10 páginas, incluida la orientación vertical y la regla de no hacer preguntas.

Confirma que entendiste el estilo, especialmente: (1) la orientación VERTICAL de página, y (2) que NO debes hacerme preguntas de aclaración porque cada instrucción trae todo completo. Luego te enviaré, una por una, las 10 instrucciones con el contenido específico de cada RF del rol "Ayudante de Cocina", cada una pidiéndote crear su Diagrama de Actividades.
```

---

## B. INSTRUCCIONES INDIVIDUALES (10 páginas — Rol: Ayudante de Cocina)

### Página 1 — RF-54: Autenticación en Dos Pasos del Ayudante de Cocina (KDS)  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-54 - Autenticación en Dos Pasos del Ayudante de Cocina (KDS)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Inicia sesión con correo  (El ayudante inicia sesión con correo y contraseña general.)
  2. [Sistema] Valida el rol "Cocina" (paso 1)  (El sistema valida el rol "Cocina" (paso 1).)
  3. [Ayudante de Cocina] Ingresa el usuario  (El ayudante ingresa el usuario y contraseña de estación en la tablet KDS.)
  4. [Sistema] Valida las credenciales de estación  (El sistema valida las credenciales de estación y carga la interfaz táctil del KDS (paso 2).)

Decisión (símbolo): ¿El administrador dio de baja al empleado (RF-30)?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza el acceso: "Cuenta inactiva""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Evita que un tercero use la tablet aunque conozca solo una de las dos claves.
  - Cumple con la regla de Cero Registro Autónomo para empleados (RF-27).
```

### Página 2 — RF-55: Visualización del Tablero KDS con Tiempo Transcurrido  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-55 - Visualización del Tablero KDS con Tiempo Transcurrido" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Inicia sesión  (El ayudante inicia sesión y accede al tablero.)
  2. [Sistema] Consulta las órdenes activas  (El sistema consulta las órdenes activas y las distribuye según su estado actual.)
  3. [Sistema] Muestra en cada tarjeta el tiempo transcurrido  (El sistema muestra en cada tarjeta el tiempo transcurrido desde la confirmación del pedido, actualizándolo en tiempo real.)

Decisión (símbolo): ¿Un pedido supera un tiempo de espera crítico?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Resalta visualmente la tarjeta para priorizarla"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Interfaz con tipografía grande y colores de alto contraste (RNF-Usabilidad).
  - Optimizado para operación táctil (RNF-Usabilidad).
```

### Página 3 — RF-56: Avance de Pedido de Pendientes a En Preparación  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-56 - Avance de Pedido de Pendientes a En Preparación" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Visualiza un pedido en la columna "Pendientes"  (El ayudante visualiza un pedido en la columna "Pendientes".)
  2. [Ayudante de Cocina] Oprime "Preparar"  (El ayudante oprime "Preparar" y comienza a cocinar.)
  3. [Sistema] Mueve la tarjeta a "En Preparación"  (El sistema mueve la tarjeta a "En Preparación" y notifica al cliente (RF-16).)

Decisión (símbolo): ¿El ayudante mueve una tarjeta a "En Preparación" por error?

  Si la respuesta es "Sí":
    → Actividad nueva: [Ayudante de Cocina] "Devuelve la tarjeta a "Pendientes""  (carril: Ayudante de Cocina)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es el primer eslabón trazable del tiempo real de preparación de cada orden.
```

### Página 4 — RF-57: Resaltado Visual de Personalizaciones  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-57 - Resaltado Visual de Personalizaciones" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Lee la tarjeta del pedido entrante  (El ayudante lee la tarjeta del pedido entrante.)
  2. [Sistema] Detecta que el objeto tiene modificadores (RF-10)  (El sistema detecta que el objeto tiene modificadores (RF-10).)
  3. [Sistema] Inyecta el estilo visual: rojo  (El sistema inyecta el estilo visual: rojo para exclusiones y verde para extras.)

Decisión (símbolo): ¿El pedido es estándar y no tiene personalizaciones?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra el nombre en fuente neutral, sin alertas"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Crucial para el éxito del modelo de cocina oculta rápida.
  - Se alimenta directamente del carrito interactivo del cliente (RF-10).
```

### Página 5 — RF-58: Alerta de Priorización por Tiempo en Cocina (SLA)  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-58 - Alerta de Priorización por Tiempo en Cocina (SLA)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Sistema] Monitorea de forma continua el tiempo transcurrido  (El sistema monitorea de forma continua el tiempo transcurrido de cada pedido en la columna "En Preparación" (RF-55).)
  2. [Sistema] Al superar los 15 minutos  (Al superar los 15 minutos, el sistema resalta automáticamente la tarjeta del pedido con un borde rojo y animación de pulso, sin necesidad de intervención del ayudante.)

Decisión (símbolo): ¿El pedido pasa a "Listos" (RF-63) antes de cumplir los 15 minutos?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Detiene el temporizador sin activar la alerta"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 2), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El umbral de 15 minutos corresponde al SLA (tiempo máximo esperado) de preparación en cocina.
  - Esta alerta es exclusivamente visual y no bloquea ni pausa la preparación del pedido.
```

### Página 6 — RF-59: Resumen Agregado de Producción por Lotes  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-59 - Resumen Agregado de Producción por Lotes" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Abre el panel de "Resumen de Producción"  (El ayudante abre el panel de "Resumen de Producción".)
  2. [Sistema] Suma las cantidades de cada producto entre todas  (El sistema suma las cantidades de cada producto entre todas las órdenes activas.)
  3. [Sistema] Despliega la tabla consolidada por producto  (El sistema despliega la tabla consolidada por producto.)

Decisión (símbolo): ¿No hay pedidos activos en el momento?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra el resumen vacío: "Sin pedidos pendientes""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Facilita cocinar por lotes en vez de una orden a la vez, optimizando tiempos en horas pico.
  - Se recalcula automáticamente con cada nueva orden confirmada (RF-41).
```

### Página 7 — RF-60: Visualización del Estado de Inventario Crítico en KDS  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-60 - Visualización del Estado de Inventario Crítico en KDS" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Está operando la pantalla  (El ayudante está operando la pantalla y cocinando.)
  2. [Sistema] Procesa las ventas recientes  (El sistema procesa las ventas recientes y actualiza el estado del inventario crítico en pantalla.)
  3. [Sistema] Resalta en rojo los insumos que cruzan  (El sistema resalta en rojo los insumos que cruzan el umbral crítico (RF-46).)

Decisión (símbolo): ¿La base de datos sufre una desincronización momentánea?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra un ícono de advertencia por desconexión"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Permite al ayudante avisar al administrador para que use el "Abastecimiento Express" (RF-45).
  - No interfiere con el espacio visual principal de las tarjetas de pedidos (Kanban).
```

### Página 8 — RF-61: Activación o Silencio de Alertas Sonoras en Cocina  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-61 - Activación o Silencio de Alertas Sonoras en Cocina" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Presiona el ícono de sonido en el KDS  (El ayudante presiona el ícono de sonido en el KDS.)
  2. [Sistema] Alterna el estado entre "Activado"  (El sistema alterna el estado entre "Activado" y "Silenciado".)
  3. [Sistema] Guarda la preferencia  (El sistema guarda la preferencia para futuras sesiones de ese usuario.)

Decisión (símbolo): ¿El ayudante silencia el sonido?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Mantiene la alerta visual de nuevo pedido"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La preferencia se guarda por usuario, no de forma global.
  - Aplica el mismo patrón de recordar preferencias que el modo claro/oscuro (RNF-Usabilidad).
```

### Página 9 — RF-62: Impresión de Tirilla o Sticker de Empaque  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-62 - Impresión de Tirilla o Sticker de Empaque" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Termina de armar el pedido  (El ayudante termina de armar el pedido y lo mete en la bolsa.)
  2. [Ayudante de Cocina] Presiona el botón "Imprimir"  (El ayudante presiona el botón "Imprimir".)
  3. [Sistema] Formatea los datos  (El sistema formatea los datos (nombre, dirección, detalle) y manda la orden a la impresora.)

Decisión (símbolo): ¿La impresora térmica está desconectada o sin papel?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra alerta: "Error de Impresora""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Requiere integración por red (LAN) o Bluetooth con una impresora térmica estándar (POS).
  - Consolida la información del pedido, incluyendo el PIN de entrega no visible (solo para el cliente).
```

### Página 10 — RF-63: Marcado de Pedido como "Listo"  `[Módulo: Ayudante de Cocina]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-63 - Marcado de Pedido como "Listo"" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Ayudante de Cocina" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Ayudante de Cocina] Sella la bolsa  (El ayudante sella la bolsa y presiona el botón "Marcar Listo".)
  2. [Sistema] Remueve la tarjeta de la vista de preparación  (El sistema remueve la tarjeta de la vista de preparación en el KDS.)
  3. [Sistema] Publica la orden en la lista de pedidos  (El sistema publica la orden en la lista de pedidos disponibles para los domiciliarios (RF-66).)
  4. [Sistema] Actualiza la barra de progreso en el celular  (El sistema actualiza la barra de progreso en el celular del cliente (RF-16).)

Decisión (símbolo): ¿El ayudante marca un pedido por error?

  Si la respuesta es "Sí":
    → Actividad nueva: [Administrador] "Devuelve el estado a "En Preparación""  (carril: Administrador)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es el puente que conecta el trabajo físico interno con la logística externa.
  - Diseñado para operar en un solo toque, pensando en las manos ocupadas del cocinero.
```
