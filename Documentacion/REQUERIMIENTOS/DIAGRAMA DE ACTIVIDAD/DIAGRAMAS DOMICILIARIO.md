# INSTRUCCIONES LUCID — DIAGRAMAS DE ACTIVIDAD — ROL: Domiciliario — HAMBURGUER COPIWAY

Documento independiente. Úsalo en un archivo/documento de Lucid **aparte** de los demás roles.

RF incluidos en este documento: RF-64 a RF-70 (7 diagramas).

---

## CÓMO USAR ESTE DOCUMENTO

1. Pega primero el **PROMPT MAESTRO** (sección A) en un documento NUEVO de Lucid para fijar el estilo visual exacto, la orientación VERTICAL de página, y la regla de NO hacer preguntas.
2. Pega luego, en orden, cada una de las 7 instrucciones (sección B). Cada una crea, en una página nueva de ese mismo documento, un DIAGRAMA DE ACTIVIDADES (UML) completo, en formato vertical, sin que la IA deba preguntarte nada.
3. Cada instrucción trae el bloque **"Decisión"** con dos ramas explícitas — "Si la respuesta es 'Sí'" y "Si la respuesta es 'No'" — cada una con su actividad exacta o su continuación exacta. Esto es justamente lo que antes generaba la pregunta de aclaración: ahora ya no hace falta preguntar nada, todo está resuelto de antemano.
4. El texto entre paréntesis en los pasos es solo referencia tuya; NO se dibuja en el diagrama.
5. El texto entre corchetes `[Módulo: ...]` en el encabezado es solo referencia tuya; no se dibuja en el diagrama.

---

## A. PROMPT MAESTRO (pegar una sola vez, primero, en este documento)

```
Vamos a crear, en un solo documento de Lucidchart, 7 DIAGRAMAS DE ACTIVIDAD (UML) — uno por página, uno por cada Requerimiento Funcional (RF) del rol "Domiciliario" — para el sistema "Hamburguer Copiway". Cada página debe contener un diagrama de actividades completo (con nodo de inicio, actividades secuenciales, un punto de decisión y nodo de fin), no un simple listado ni un diagrama de otro tipo. Todas las páginas deben tener EXACTAMENTE el mismo estilo visual, sin variaciones entre ellas. Sigue esta plantilla al pie de la letra.

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
- Dos carriles (swimlanes) en formato tabla simple, SIN relleno de color en el encabezado ni en el fondo del carril: solo una línea vertical delgada que separa las columnas, y el nombre del actor como texto plano centrado arriba de cada columna (izquierda: "Domiciliario"; derecha: "Sistema"). No uses contenedores de color tipo "Añadir título" para los carriles.

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

FLUJO DEL DIAGRAMA DE ACTIVIDADES (aplica en cada una de las 7 páginas):
- Inicio → pasos de la Secuencia Normal en orden, cada uno en el carril correspondiente (Actor o Sistema) → rombo de decisión en el punto de validación (ubicado justo después del último paso ya dibujado hasta ese momento, según se indique en cada instrucción).
- Cada instrucción individual te dice EXACTAMENTE qué dibujar en la rama "Sí" y qué dibujar en la rama "No" — sigue eso literalmente, no la inviertas y no la completes por tu cuenta.
- Ambas ramas siempre terminan en el mismo nodo Fin.

REGLAS GENERALES:
- Cada página es un DIAGRAMA DE ACTIVIDADES (UML), no un flujograma libre, ni un diagrama de casos de uso, ni un mockup.
- No agregues pasos, actores, rótulos ni colores distintos a los aquí definidos.
- Título de cada página (pestaña): "<Código> - <Nombre del RF>".
- Aplica este mismo estilo, sin excepción, en las 7 páginas, incluida la orientación vertical y la regla de no hacer preguntas.

Confirma que entendiste el estilo, especialmente: (1) la orientación VERTICAL de página, y (2) que NO debes hacerme preguntas de aclaración porque cada instrucción trae todo completo. Luego te enviaré, una por una, las 7 instrucciones con el contenido específico de cada RF del rol "Domiciliario", cada una pidiéndote crear su Diagrama de Actividades.
```

---

## B. INSTRUCCIONES INDIVIDUALES (7 páginas — Rol: Domiciliario)

### Página 1 — RF-64: Autenticación en Dos Pasos del Domiciliario  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-64 - Autenticación en Dos Pasos del Domiciliario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] Abre la app e ingresa correo  (El domiciliario abre la app e ingresa correo y contraseña general.)
  2. [Sistema] Valida el rol de "Domiciliario" (paso 1)  (El sistema valida el rol de "Domiciliario" (paso 1).)
  3. [Domiciliario] Ingresa usuario  (El domiciliario ingresa usuario y contraseña de estación en el Panel de Domiciliario.)
  4. [Sistema] Valida las credenciales de estación  (El sistema valida las credenciales de estación y permite el acceso, conectando el dispositivo al servidor en tiempo real (paso 2).)

Decisión (símbolo): ¿Las credenciales son incorrectas o el domiciliario fue suspendido (RF-30)?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Deniega el acceso: "Usuario no autorizado""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Evita el uso de sistemas de registro público (RF-27).
  - La sesión debe estar optimizada para dispositivos móviles (RNF-Usabilidad).
```

### Página 2 — RF-65: Marcado de Disponibilidad del Domiciliario  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-65 - Marcado de Disponibilidad del Domiciliario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] Inicia su turno  (El domiciliario inicia su turno y activa "Disponible".)
  2. [Sistema] Lo habilita  (El sistema lo habilita para recibir/tomar pedidos y actualiza su estado en el mapa del administrador.)
  3. [Domiciliario] Finaliza su turno  (El domiciliario finaliza su turno y marca "Desconectado".)
  4. [Sistema] Deja de asignarle nuevos pedidos  (El sistema deja de asignarle nuevos pedidos.)

Decisión (símbolo): ¿El domiciliario tiene un pedido activo en curso?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Permite marcar "Desconectado" tras finalizar la entrega"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El estado se refleja en tiempo real en el mapa del administrador (RF-49).
  - Complementa la lista de pedidos disponibles (RF-66).
```

### Página 3 — RF-66: Autoasignación de Pedidos Disponibles  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-66 - Autoasignación de Pedidos Disponibles" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] El domiciliario  (El domiciliario, en estado "Disponible" (RF-65), abre la lista de pedidos listos (RF-63).)
  2. [Sistema] Despliega los pedidos disponibles  (El sistema despliega los pedidos disponibles para recoger.)
  3. [Domiciliario] Presiona "Tomar Pedido"  (El domiciliario presiona "Tomar Pedido".)
  4. [Sistema] Le asigna la orden  (El sistema le asigna la orden y la retira de la lista para los demás domiciliarios.)

Decisión (símbolo): ¿Dos domiciliarios intentan tomar el mismo pedido casi simultáneamente?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Asigna al primero y notifica al segundo"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Evita la necesidad de que el administrador asigne manualmente cada pedido.
  - El pedido asignado pasa a mostrarse en el mapa del administrador (RF-49).
```

### Página 4 — RF-67: Mapa Interactivo y Navegación Externa  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-67 - Mapa Interactivo y Navegación Externa" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] Toma un pedido (RF-66)  (El domiciliario toma un pedido (RF-66).)
  2. [Sistema] Traza la ruta en el mapa interactivo integrado  (El sistema traza la ruta en el mapa interactivo integrado.)
  3. [Domiciliario] Presiona "Abrir en Waze" o "Abrir en Google  (El domiciliario presiona "Abrir en Waze" o "Abrir en Google Maps".)
  4. [Sistema] Invoca la app externa correspondiente con el destino  (El sistema invoca la app externa correspondiente con el destino precargado.)

Decisión (símbolo): ¿El domiciliario no tiene instalada la app externa seleccionada?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Redirige a la tienda de apps o usa el mapa interno"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Fundamental para la agilidad en la última milla.
  - Consume servicios de geolocalización de terceros.
```

### Página 5 — RF-68: Botón de Contacto Temporal Protegido  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-68 - Botón de Contacto Temporal Protegido" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] Presiona "Contactar Cliente" porque no halla la casa  (El domiciliario presiona "Contactar Cliente" porque no halla la casa.)
  2. [Sistema] Verifica que la orden está en estado  (El sistema verifica que la orden está en estado "En camino".)
  3. [Sistema] Lanza el acceso directo a WhatsApp/Llamada  (El sistema lanza el acceso directo a WhatsApp/Llamada para ejecutar la comunicación.)

Decisión (símbolo): ¿El domiciliario intenta presionar el botón antes de salir?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Oculta o deshabilita el botón de contacto"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es una capa estricta de seguridad de datos (RNF-Seguridad).
  - El número del cliente nunca queda expuesto de forma permanente en la app.
```

### Página 6 — RF-69: Validación de Entrega mediante PIN  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-69 - Validación de Entrega mediante PIN" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] Entrega la bolsa  (El domiciliario entrega la bolsa y solicita el PIN al cliente.)
  2. [Domiciliario] Ingresa el PIN de 4 dígitos  (El domiciliario ingresa el PIN de 4 dígitos en su app.)
  3. [Sistema] Valida el PIN contra el generado  (El sistema valida el PIN contra el generado para ese pedido (RF-17) y habilita el botón "Entregado".)

Decisión (símbolo): ¿El PIN ingresado es incorrecto?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza la validación y permite reintentar"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es un control anti-fraude que impide marcar entregas sin la confirmación física del cliente.
  - Habilita directamente el cierre de ciclo (RF-70).
```

### Página 7 — RF-70: Confirmación de Entrega (Cierre de Ciclo)  `[Módulo: Domiciliario]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-70 - Confirmación de Entrega (Cierre de Ciclo)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Domiciliario" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Domiciliario] Valida el PIN de entrega (RF-69)  (El domiciliario valida el PIN de entrega (RF-69).)
  2. [Domiciliario] Presiona el botón "Entregado"  (El domiciliario presiona el botón "Entregado".)
  3. [Sistema] Marca el registro como completado  (El sistema marca el registro como completado y lo borra del mapa activo.)
  4. [Sistema] Notifica al cliente que puede dejar una reseña  (El sistema notifica al cliente que puede dejar una reseña (RF-19).)
  5. [Sistema] Consolida la venta  (El sistema consolida la venta para el reporte de cierre (RF-50).)

Decisión (símbolo): ¿El cliente no sale o no recuerda su PIN?

  Si la respuesta es "Sí":
    → Actividad nueva: [Domiciliario] "Se comunica con el administrador para forzar el cierre"  (carril: Domiciliario)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 5), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Este paso es el que alimenta la analítica de ventas diarias.
  - El domiciliario debe marcar "Disponible" nuevamente (RF-65) para recibir su siguiente pedido.
```
