# INSTRUCCIONES LUCID — DIAGRAMAS DE ACTIVIDAD — ROL: Cliente — HAMBURGUER COPIWAY

Documento independiente. Úsalo en un archivo/documento de Lucid **aparte** de los demás roles.

RF incluidos en este documento: RF-01 a RF-24 (24 diagramas).

---

## CÓMO USAR ESTE DOCUMENTO

1. Pega primero el **PROMPT MAESTRO** (sección A) en un documento NUEVO de Lucid para fijar el estilo visual exacto, la orientación VERTICAL de página, y la regla de NO hacer preguntas.
2. Pega luego, en orden, cada una de las 24 instrucciones (sección B). Cada una crea, en una página nueva de ese mismo documento, un DIAGRAMA DE ACTIVIDADES (UML) completo, en formato vertical, sin que la IA deba preguntarte nada.
3. Cada instrucción trae el bloque **"Decisión"** con dos ramas explícitas — "Si la respuesta es 'Sí'" y "Si la respuesta es 'No'" — cada una con su actividad exacta o su continuación exacta. Esto es justamente lo que antes generaba la pregunta de aclaración: ahora ya no hace falta preguntar nada, todo está resuelto de antemano.
4. El texto entre paréntesis en los pasos es solo referencia tuya; NO se dibuja en el diagrama.
5. El texto entre corchetes `[Módulo: ...]` en el encabezado es solo referencia tuya; no se dibuja en el diagrama.

---

## A. PROMPT MAESTRO (pegar una sola vez, primero, en este documento)

```
Vamos a crear, en un solo documento de Lucidchart, 24 DIAGRAMAS DE ACTIVIDAD (UML) — uno por página, uno por cada Requerimiento Funcional (RF) del rol "Cliente" — para el sistema "Hamburguer Copiway". Cada página debe contener un diagrama de actividades completo (con nodo de inicio, actividades secuenciales, un punto de decisión y nodo de fin), no un simple listado ni un diagrama de otro tipo. Todas las páginas deben tener EXACTAMENTE el mismo estilo visual, sin variaciones entre ellas. Sigue esta plantilla al pie de la letra.

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
- Dos carriles (swimlanes) en formato tabla simple, SIN relleno de color en el encabezado ni en el fondo del carril: solo una línea vertical delgada que separa las columnas, y el nombre del actor como texto plano centrado arriba de cada columna (izquierda: "Cliente"; derecha: "Sistema"). No uses contenedores de color tipo "Añadir título" para los carriles.

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

FLUJO DEL DIAGRAMA DE ACTIVIDADES (aplica en cada una de las 24 páginas):
- Inicio → pasos de la Secuencia Normal en orden, cada uno en el carril correspondiente (Actor o Sistema) → rombo de decisión en el punto de validación (ubicado justo después del último paso ya dibujado hasta ese momento, según se indique en cada instrucción).
- Cada instrucción individual te dice EXACTAMENTE qué dibujar en la rama "Sí" y qué dibujar en la rama "No" — sigue eso literalmente, no la inviertas y no la completes por tu cuenta.
- Ambas ramas siempre terminan en el mismo nodo Fin.

REGLAS GENERALES:
- Cada página es un DIAGRAMA DE ACTIVIDADES (UML), no un flujograma libre, ni un diagrama de casos de uso, ni un mockup.
- No agregues pasos, actores, rótulos ni colores distintos a los aquí definidos.
- Título de cada página (pestaña): "<Código> - <Nombre del RF>".
- Aplica este mismo estilo, sin excepción, en las 24 páginas, incluida la orientación vertical y la regla de no hacer preguntas.

Confirma que entendiste el estilo, especialmente: (1) la orientación VERTICAL de página, y (2) que NO debes hacerme preguntas de aclaración porque cada instrucción trae todo completo. Luego te enviaré, una por una, las 24 instrucciones con el contenido específico de cada RF del rol "Cliente", cada una pidiéndote crear su Diagrama de Actividades.
```

---

## B. INSTRUCCIONES INDIVIDUALES (24 páginas — Rol: Cliente)

### Página 1 — RF-01: Aceptación de Políticas de Datos (Habeas Data)  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-01 - Aceptación de Políticas de Datos (Habeas Data)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Llena sus datos  (El cliente llena sus datos y marca la casilla de aceptación.)
  2. [Sistema] Detecta la selección  (El sistema detecta la selección y habilita el botón de envío.)
  3. [Cliente] Presiona el botón  (El cliente presiona el botón para registrarse.)
  4. [Sistema] Procesa la solicitud  (El sistema procesa la solicitud y crea el perfil en la base de datos (RF-02).)

Decisión (símbolo): ¿El cliente intenta enviar el formulario sin marcar?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Bloquea el registro y muestra error"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es un paso estrictamente bloqueante para nuevos usuarios.
  - Aplica exclusivamente para el registro del módulo Cliente.
```

### Página 2 — RF-02: Creación de Perfil de Usuario  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-02 - Creación de Perfil de Usuario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa sus nombres  (El cliente ingresa sus nombres, apellidos, correo, teléfono y fecha de nacimiento.)
  2. [Sistema] Valida el formato de cada campo (RF-22)  (El sistema valida el formato de cada campo (RF-22).)
  3. [Cliente] Confirma el registro  (El cliente confirma el registro.)
  4. [Sistema] Crea el perfil  (El sistema crea el perfil y le otorga acceso a la plataforma.)

Decisión (símbolo): ¿El correo o el teléfono ya está registrado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza la creación del perfil"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El registro es 100% autónomo por parte del cliente.
  - La fecha de nacimiento se utiliza posteriormente para el descuento de cumpleaños (RF-06).
```

### Página 3 — RF-03: Inicio de Sesión de Cliente  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-03 - Inicio de Sesión de Cliente" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Digita sus credenciales de acceso  (El cliente digita sus credenciales de acceso.)
  2. [Sistema] Valida la información contra la base de datos  (El sistema valida la información contra la base de datos.)
  3. [Cliente] Presiona "Entrar"  (El cliente presiona "Entrar".)
  4. [Sistema] Otorga acceso  (El sistema otorga acceso y carga el catálogo dinámico (RF-07).)

Decisión (símbolo): ¿Las credenciales son incorrectas?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza el acceso y muestra error"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La sesión debe mantenerse activa para persistir el carrito (RF-20).
  - Si el cliente olvidó su contraseña, puede recuperarla en dos pasos (RF-21).
```

### Página 4 — RF-04: Acumulación de Puntos por Compra  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-04 - Acumulación de Puntos por Compra" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Confirma un pedido (RF-12)  (El cliente confirma un pedido (RF-12).)
  2. [Sistema] Calcula los puntos correspondientes  (El sistema calcula los puntos correspondientes (1 punto Copiway por cada $1.000 del total pagado).)
  3. [Sistema] Suma los puntos al saldo acumulado del perfil  (El sistema suma los puntos al saldo acumulado del perfil del usuario.)

Decisión (símbolo): ¿El pedido es cancelado antes de confirmarse?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "No acredita puntos al cliente"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La tasa de conversión es fija: 1 punto Copiway por cada $1.000 del valor total pagado.
  - Operación ejecutada automáticamente al confirmarse el pedido.
  - Los puntos quedan visibles para el cliente en su perfil (RF-23).
```

### Página 5 — RF-05: Visualización de Puntos Copiway Ganados por Pedido  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-05 - Visualización de Puntos Copiway Ganados por Pedido" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Confirma  (El cliente confirma y paga su pedido (RF-12).)
  2. [Sistema] Calcula los puntos ganados (RF-04)  (El sistema calcula los puntos ganados (RF-04) y los muestra en la pantalla de confirmación junto con el saldo total acumulado.)
  3. [Cliente] Ingresa posteriormente a su historial de pedidos  (El cliente ingresa posteriormente a su historial de pedidos y selecciona uno.)
  4. [Sistema] Despliega el detalle del pedido incluyendo los puntos  (El sistema despliega el detalle del pedido incluyendo los puntos Copiway que generó esa compra específica.)

Decisión (símbolo): ¿El pedido fue registrado manualmente como "Efectivo al Entregar" (RF-43)?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra los puntos como pendientes hasta la entrega"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El saldo de puntos mostrado es acumulativo y se actualiza en tiempo real en el perfil del cliente (RF-23).
  - Esta visualización informa el saldo; la redención de puntos no forma parte del alcance actual del sistema.
```

### Página 6 — RF-06: Descuento Automático de Cumpleaños  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-06 - Descuento Automático de Cumpleaños" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Avanza a la pantalla de pago  (El cliente avanza a la pantalla de pago en el día de su cumpleaños.)
  2. [Sistema] Detecta la coincidencia de fecha  (El sistema detecta la coincidencia de fecha y aplica el 15% de descuento automáticamente.)
  3. [Sistema] Despliega el nuevo total con el descuento reflejado  (El sistema despliega el nuevo total con el descuento reflejado.)

Decisión (símbolo): ¿No es el día del cumpleaños del cliente?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Omite el descuento y calcula total estándar"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El porcentaje (15%) es fijo y aplica sobre el subtotal de productos, no sobre el envío.
  - El descuento es de aplicación única durante el día de cumpleaños.
```

### Página 7 — RF-07: Visualización de Catálogo Dinámico con Bloqueo por Receta  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-07 - Visualización de Catálogo Dinámico con Bloqueo por Receta" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa a la sección del catálogo  (El cliente ingresa a la sección del catálogo.)
  2. [Sistema] Consulta la base de datos  (El sistema consulta la base de datos y despliega los productos en menos de 4 segundos.)
  3. [Sistema] Cruza la receta (RF-34) de cada producto  (El sistema cruza la receta (RF-34) de cada producto contra el inventario y marca como "Agotado" los que carezcan de algún insumo.)

Decisión (símbolo): ¿No hay productos activos en la base de datos?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra el mensaje "Menú no disponible""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Incluye las líneas de preparación de comidas y crudos.
  - El catálogo está bajo la gestión directa del Administrador (RF-32).
```

### Página 8 — RF-08: Creador Interactivo (Minijuego)  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-08 - Creador Interactivo (Minijuego)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Abre la opción de Creador Interactivo  (El cliente abre la opción de Creador Interactivo.)
  2. [Sistema] Carga las capas visuales de los ingredientes disponibles  (El sistema carga las capas visuales de los ingredientes disponibles.)
  3. [Cliente] Intenta seleccionar un ingrediente sin inventario  (El cliente intenta seleccionar un ingrediente sin inventario.)
  4. [Sistema] Bloquea la imagen  (El sistema bloquea la imagen y muestra el aviso "Agotado".)

Decisión (símbolo): ¿Todos los ingredientes están disponibles?

  Si la respuesta es "Sí":
    → Actividad nueva: [Cliente] "Finaliza el armado y lo añade al carrito"  (carril: Cliente)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Depende directamente de la integridad de datos del inventario en tiempo real (RF-45).
  - El margen de ganancia por insumo se configura desde el panel del administrador (RF-52).
```

### Página 9 — RF-09: Recompra en 1 Clic con Validación de Existencias  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-09 - Recompra en 1 Clic con Validación de Existencias" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Presiona el botón de "Recompra en 1 Clic"  (El cliente presiona el botón de "Recompra en 1 Clic".)
  2. [Sistema] Consulta el historial  (El sistema consulta el historial y valida existencias contra el inventario (RF-07).)
  3. [Sistema] Clona el carrito anterior con sus personalizaciones  (El sistema clona el carrito anterior con sus personalizaciones y redirige a la pantalla de pago.)

Decisión (símbolo): ¿Alguno de los ingredientes del pedido anterior está agotado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Avisa qué productos no puede duplicar"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Mejora significativa para la experiencia de usuario (UX).
  - Clona incluso las exclusiones (ej. "sin cebolla") del pedido histórico.
```

### Página 10 — RF-10: Personalización Interactiva del Pedido  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-10 - Personalización Interactiva del Pedido" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Selecciona la opción de quitar un ingrediente  (El cliente selecciona la opción de quitar un ingrediente.)
  2. [Sistema] Marca el producto internamente  (El sistema marca el producto internamente y no altera el precio base.)
  3. [Cliente] Añade un ingrediente extra  (El cliente añade un ingrediente extra.)
  4. [Sistema] Suma el costo adicional al precio total  (El sistema suma el costo adicional al precio total del producto en el carrito.)

Decisión (símbolo): ¿El cliente quita ingredientes?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Evita descontar ese insumo del inventario"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Esta personalización alimenta el Tablero KDS de la cocina (RF-57).
  - Afecta directamente la fórmula del auto-descuento (escandallo) (RF-42).
```

### Página 11 — RF-11: Gestión Libre del Carrito de Compras  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-11 - Gestión Libre del Carrito de Compras" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Abre su carrito de compras  (El cliente abre su carrito de compras.)
  2. [Sistema] Despliega los productos actuales  (El sistema despliega los productos actuales y el subtotal.)
  3. [Cliente] Elimina o modifica un producto  (El cliente elimina o modifica un producto.)
  4. [Sistema] Recalcula el total  (El sistema recalcula el total y mantiene la orden abierta (sin bloquear).)

Decisión (símbolo): ¿El cliente decide vaciar el carrito por completo?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Elimina todos los productos y reinicia el total"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Esta libertad de edición se pierde por completo una vez confirmado el pedido (RF-12).
  - Se apoya en la persistencia de sesión (RF-20).
```

### Página 12 — RF-12: Confirmación del Pedido y Bloqueo (Punto de No Retorno)  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-12 - Confirmación del Pedido y Bloqueo (Punto de No Retorno)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Elige método de pago  (El cliente elige método de pago y confirma el pedido.)
  2. [Sistema] Procesa la confirmación  (El sistema procesa la confirmación (validación bancaria si es digital, o registro si es efectivo).)
  3. [Sistema] Bloquea el pedido permanentemente  (El sistema bloquea el pedido permanentemente y lo envía a la cocina (RF-41).)

Decisión (símbolo): ¿El pago digital falla o es rechazado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Deja el carrito desbloqueado para reintentar el pago"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Cumple con la regla de negocio "Punto de No Retorno".
  - Genera automáticamente el PIN de entrega de 4 dígitos (RF-17).
  - Aplica la regla de negocio "Cero Crédito": el pedido siempre queda garantizado como pagado, sin dejar la opción de fiar.
```

### Página 13 — RF-13: Selección de Método de Pago  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-13 - Selección de Método de Pago" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Llega a la pantalla de pago  (El cliente llega a la pantalla de pago y elige un método.)
  2. [Sistema] Despliega las opciones bancarias (si es digital)  (El sistema despliega las opciones bancarias (si es digital) o confirma la modalidad contra entrega.)
  3. [Cliente] Confirma la selección  (El cliente confirma la selección.)
  4. [Sistema] Asocia el método elegido al pedido  (El sistema asocia el método elegido al pedido y avanza al bloqueo (RF-12).)

Decisión (símbolo): ¿El cliente selecciona "Efectivo al Entregar"?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Omite la pasarela y pasa a confirmación"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El pago en efectivo será liquidado por el domiciliario frente a su base de efectivo asignada (RF-50).
  - El pago digital debe cumplir estándares PCI-DSS (RNF-Seguridad).
```

### Página 14 — RF-14: Visualización de Tarifa Plana de Domicilio  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-14 - Visualización de Tarifa Plana de Domicilio" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Avanza a la pantalla final de pago  (El cliente avanza a la pantalla final de pago.)
  2. [Sistema] Consulta el valor de la tarifa plana configurada  (El sistema consulta el valor de la tarifa plana configurada (RF-51).)
  3. [Sistema] Suma este valor al subtotal de los productos  (El sistema suma este valor al subtotal de los productos y despliega el Total Final.)

Decisión (símbolo): ¿El administrador actualiza el valor de la tarifa plana?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Actualiza la tarifa solo en carritos nuevos"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La tarifa plana aplica para toda la ciudad sin zonificación.
  - El valor es inyectado desde el módulo Administrador.
```

### Página 15 — RF-15: Bloqueo por Horario de Atención  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-15 - Bloqueo por Horario de Atención" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa a su carrito  (El cliente ingresa a su carrito para finalizar la compra.)
  2. [Sistema] Compara la hora del servidor con el horario  (El sistema compara la hora del servidor con el horario de apertura y cierre configurado.)
  3. [Sistema] Detecta que está fuera de horario  (El sistema detecta que está fuera de horario, bloquea el botón y muestra el aviso "Negocio Cerrado".)

Decisión (símbolo): ¿El administrador activó la pausa de emergencia (RF-53)?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Mantiene el bloqueo aunque esté en horario"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El horario de atención es un parámetro dinámico configurable por el Administrador.
  - Evita la frustración del cliente y el cobro de dinero por un servicio no disponible.
```

### Página 16 — RF-16: Rastreo de Estado del Pedido  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-16 - Rastreo de Estado del Pedido" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa a la pestaña "Mis Pedidos Activos"  (El cliente ingresa a la pestaña "Mis Pedidos Activos".)
  2. [Sistema] Lee el estado de la orden transmitido  (El sistema lee el estado de la orden transmitido por el KDS de cocina o la app del domiciliario.)
  3. [Sistema] Ilumina el estado correspondiente en el indicador visual  (El sistema ilumina el estado correspondiente en el indicador visual.)

Decisión (símbolo): ¿La conexión a internet del cliente se interrumpe?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Congela el estado y sincroniza al reconectar"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Se actualiza mediante tecnologías de baja latencia como WebSockets.
  - Los cambios de estado son gatillados por el ayudante de cocina (RF-63) o el domiciliario (RF-70).
```

### Página 17 — RF-17: Visualización del PIN de Entrega  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-17 - Visualización del PIN de Entrega" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Confirma su pedido  (El cliente confirma su pedido.)
  2. [Sistema] Genera aleatoriamente un PIN de 4 dígitos  (El sistema genera aleatoriamente un PIN de 4 dígitos y lo asocia a la orden.)
  3. [Cliente] Consulta su pantalla de rastreo (RF-16)  (El cliente consulta su pantalla de rastreo (RF-16).)
  4. [Sistema] Despliega el PIN de forma visible  (El sistema despliega el PIN de forma visible y destacada.)

Decisión (símbolo): ¿El domiciliario intenta marcar la entrega sin solicitar el PIN correcto?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza la acción de marcar entregado"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El PIN es de un solo uso, válido únicamente para el pedido que lo generó.
  - Refuerza la seguridad de la última milla junto con el contacto temporal (RF-68).
```

### Página 18 — RF-18: Notificación de Despacho (Logística)  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-18 - Notificación de Despacho (Logística)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Toma el pedido asignado  (El domiciliario toma el pedido asignado.)
  2. [Sistema] Cambia el estado de la orden  (El sistema cambia el estado de la orden a "En camino".)
  3. [Sistema] El servidor backend dispara el mensaje al proveedor  (El servidor backend dispara el mensaje al proveedor de mensajería usando el teléfono registrado del cliente.)

Decisión (símbolo): ¿La API de WhatsApp falla o el cliente no tiene número compatible?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Envía un SMS tradicional como respaldo"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Requiere la integración segura con un proveedor de mensajería.
  - Se usa el teléfono validado y capturado en el RF-02.
```

### Página 19 — RF-19: Sistema de Calificaciones y Reseñas  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-19 - Sistema de Calificaciones y Reseñas" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Abre la app después de recibir el pedido  (El cliente abre la app después de recibir el pedido y visualiza el formulario de reseña.)
  2. [Sistema] Despliega la escala de 1 a 5 estrellas  (El sistema despliega la escala de 1 a 5 estrellas y un campo de comentario opcional.)
  3. [Cliente] Asigna una puntuación  (El cliente asigna una puntuación y, si lo desea, redacta su opinión, presionando "Enviar".)
  4. [Sistema] Guarda la calificación  (El sistema guarda la calificación y notifica la métrica al panel del administrador (RF-36).)

Decisión (símbolo): ¿El cliente decide ignorar o cerrar la pantalla de calificación?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Guarda el registro como "Sin calificar""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Solo se activa si el estado final del pedido es estrictamente "Entregado" (RF-70).
  - El comentario es opcional; la puntuación de 1 a 5 es obligatoria para registrar la reseña.
```

### Página 20 — RF-20: Persistencia del Carrito de Compras  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-20 - Persistencia del Carrito de Compras" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Agrega productos al carrito  (El cliente agrega productos al carrito y cierra la pestaña por accidente.)
  2. [Sistema] Sincroniza el estado actual del carrito  (El sistema sincroniza el estado actual del carrito con la base de datos.)
  3. [Cliente] Vuelve a ingresar  (El cliente vuelve a ingresar y abre el carrito.)
  4. [Sistema] Restaura exactamente los mismos productos  (El sistema restaura exactamente los mismos productos y personalizaciones previas.)

Decisión (símbolo): ¿El cliente cierra su sesión de manera manual?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Puede limpiar la persistencia local del carrito"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Mejora sustancial de la retención y la conversión de ventas.
  - Se apoya en el inicio de sesión obligatorio (RF-03).
```

### Página 21 — RF-21: Recuperación de Contraseña en Dos Pasos  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-21 - Recuperación de Contraseña en Dos Pasos" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Selecciona "¿Olvidaste tu contraseña?" e ingresa su correo  (El cliente selecciona "¿Olvidaste tu contraseña?" e ingresa su correo o celular registrado.)
  2. [Sistema] Envía un código de verificación al medio registrado  (El sistema envía un código de verificación al medio registrado (paso 1).)
  3. [Cliente] Ingresa el código  (El cliente ingresa el código y define una nueva contraseña.)
  4. [Sistema] Valida el código  (El sistema valida el código, actualiza la contraseña (hash) en la base de datos y notifica el cambio (paso 2).)

Decisión (símbolo): ¿El correo o celular no está registrado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra mensaje genérico por seguridad"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Las contraseñas se almacenan siempre con hashing seguro (RNF-Seguridad).
  - El código de verificación tiene una vigencia limitada por motivos de seguridad.
```

### Página 22 — RF-22: Validación de Registro sin Duplicados (Clientes y Empleados)  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-22 - Validación de Registro sin Duplicados (Clientes y Empleados)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa su correo y/o teléfono en el formulario  (El cliente ingresa su correo y/o teléfono en el formulario de registro.)
  2. [Sistema] Consulta simultáneamente la tabla de clientes  (El sistema consulta simultáneamente la tabla de clientes y la tabla de empleados buscando coincidencias exactas.)
  3. [Sistema] Confirma que el dato está disponible  (El sistema confirma que el dato está disponible y permite continuar con la creación del perfil (RF-02).)

Decisión (símbolo): ¿El correo o el teléfono ya existen en cualquiera?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Bloquea el registro e invita a iniciar sesión"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Se ejecuta como validación previa a la creación efectiva del perfil.
  - Protege la integridad de los datos, ya que la arquitectura del sistema no usa una tabla única de usuarios.
```

### Página 23 — RF-23: Edición de Perfil y Dirección Predeterminada  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-23 - Edición de Perfil y Dirección Predeterminada" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa a "Mi Perfil"  (El cliente ingresa a "Mi Perfil" y edita sus datos o su dirección.)
  2. [Sistema] Valida el formato de los campos modificados  (El sistema valida el formato de los campos modificados.)
  3. [Cliente] Presiona "Guardar Cambios"  (El cliente presiona "Guardar Cambios".)
  4. [Sistema] Actualiza el registro  (El sistema actualiza el registro y confirma la operación.)

Decisión (símbolo): ¿El cliente intenta cambiar su correo o teléfono?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza el cambio de correo o teléfono"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La dirección predeterminada se precarga automáticamente en cada nuevo checkout, sin impedir su edición puntual.
  - No incluye el cambio de contraseña, que se gestiona en un flujo separado (RF-24).
```

### Página 24 — RF-24: Cambio Voluntario de Contraseña  `[Módulo: Cliente]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-24 - Cambio Voluntario de Contraseña" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Cliente" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Cliente] Ingresa a "Seguridad" dentro de su perfil  (El cliente ingresa a "Seguridad" dentro de su perfil.)
  2. [Sistema] Solicita la contraseña actual  (El sistema solicita la contraseña actual y la nueva contraseña.)
  3. [Cliente] Confirma el cambio  (El cliente confirma el cambio.)
  4. [Sistema] Valida la contraseña actual  (El sistema valida la contraseña actual, actualiza el hash y cierra las demás sesiones activas.)

Decisión (símbolo): ¿La contraseña actual ingresada es incorrecta?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza el cambio y solicita reintentar"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es independiente del flujo de recuperación en dos pasos (RF-21).
  - Refuerza la seguridad proactiva de la cuenta del cliente.
```
