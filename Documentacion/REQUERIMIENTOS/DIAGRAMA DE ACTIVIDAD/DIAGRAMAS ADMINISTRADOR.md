# INSTRUCCIONES LUCID — DIAGRAMAS DE ACTIVIDAD — ROL: Administrador — HAMBURGUER COPIWAY

Documento independiente. Úsalo en un archivo/documento de Lucid **aparte** de los demás roles.

RF incluidos en este documento: RF-26 a RF-53 (28 diagramas).

---

## CÓMO USAR ESTE DOCUMENTO

1. Pega primero el **PROMPT MAESTRO** (sección A) en un documento NUEVO de Lucid para fijar el estilo visual exacto, la orientación VERTICAL de página, y la regla de NO hacer preguntas.
2. Pega luego, en orden, cada una de las 28 instrucciones (sección B). Cada una crea, en una página nueva de ese mismo documento, un DIAGRAMA DE ACTIVIDADES (UML) completo, en formato vertical, sin que la IA deba preguntarte nada.
3. Cada instrucción trae el bloque **"Decisión"** con dos ramas explícitas — "Si la respuesta es 'Sí'" y "Si la respuesta es 'No'" — cada una con su actividad exacta o su continuación exacta. Esto es justamente lo que antes generaba la pregunta de aclaración: ahora ya no hace falta preguntar nada, todo está resuelto de antemano.
4. El texto entre paréntesis en los pasos es solo referencia tuya; NO se dibuja en el diagrama.
5. El texto entre corchetes `[Módulo: ...]` en el encabezado es solo referencia tuya; no se dibuja en el diagrama.

---

## A. PROMPT MAESTRO (pegar una sola vez, primero, en este documento)

```
Vamos a crear, en un solo documento de Lucidchart, 28 DIAGRAMAS DE ACTIVIDAD (UML) — uno por página, uno por cada Requerimiento Funcional (RF) del rol "Administrador" — para el sistema "Hamburguer Copiway". Cada página debe contener un diagrama de actividades completo (con nodo de inicio, actividades secuenciales, un punto de decisión y nodo de fin), no un simple listado ni un diagrama de otro tipo. Todas las páginas deben tener EXACTAMENTE el mismo estilo visual, sin variaciones entre ellas. Sigue esta plantilla al pie de la letra.

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
- Dos carriles (swimlanes) en formato tabla simple, SIN relleno de color en el encabezado ni en el fondo del carril: solo una línea vertical delgada que separa las columnas, y el nombre del actor como texto plano centrado arriba de cada columna (izquierda: "Administrador"; derecha: "Sistema"). No uses contenedores de color tipo "Añadir título" para los carriles.

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

FLUJO DEL DIAGRAMA DE ACTIVIDADES (aplica en cada una de las 28 páginas):
- Inicio → pasos de la Secuencia Normal en orden, cada uno en el carril correspondiente (Actor o Sistema) → rombo de decisión en el punto de validación (ubicado justo después del último paso ya dibujado hasta ese momento, según se indique en cada instrucción).
- Cada instrucción individual te dice EXACTAMENTE qué dibujar en la rama "Sí" y qué dibujar en la rama "No" — sigue eso literalmente, no la inviertas y no la completes por tu cuenta.
- Ambas ramas siempre terminan en el mismo nodo Fin.

REGLAS GENERALES:
- Cada página es un DIAGRAMA DE ACTIVIDADES (UML), no un flujograma libre, ni un diagrama de casos de uso, ni un mockup.
- No agregues pasos, actores, rótulos ni colores distintos a los aquí definidos.
- Título de cada página (pestaña): "<Código> - <Nombre del RF>".
- Aplica este mismo estilo, sin excepción, en las 28 páginas, incluida la orientación vertical y la regla de no hacer preguntas.

Confirma que entendiste el estilo, especialmente: (1) la orientación VERTICAL de página, y (2) que NO debes hacerme preguntas de aclaración porque cada instrucción trae todo completo. Luego te enviaré, una por una, las 28 instrucciones con el contenido específico de cada RF del rol "Administrador", cada una pidiéndote crear su Diagrama de Actividades.
```

---

## B. INSTRUCCIONES INDIVIDUALES (28 páginas — Rol: Administrador)

### Página 1 — RF-26: Autenticación Maestra del Administrador  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-26 - Autenticación Maestra del Administrador" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa sus credenciales  (El administrador ingresa sus credenciales.)
  2. [Sistema] Verifica el rol  (El sistema verifica el rol y cruza los hashes de contraseña.)
  3. [Administrador] Presiona "Ingresar"  (El administrador presiona "Ingresar".)
  4. [Sistema] Autoriza el acceso completo a los módulos  (El sistema autoriza el acceso completo a los módulos de gestión.)

Decisión (símbolo): ¿El intento de acceso proviene de una vista externa de registro con correo de administrador?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Bloquea el intento de registro externo"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Cumple con las normativas de seguridad RBAC (Role-Based Access Control).
  - Es el único actor que no tiene un formulario de "Crear cuenta" en el frontend.
```

### Página 2 — RF-27: Gestión Jerárquica de Cuentas de Personal  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-27 - Gestión Jerárquica de Cuentas de Personal" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Accede a la pestaña "Gestión de Personal"  (El administrador accede a la pestaña "Gestión de Personal".)
  2. [Sistema] Muestra la lista de empleados  (El sistema muestra la lista de empleados y el botón "Nuevo Empleado".)
  3. [Administrador] Llena los datos  (El administrador llena los datos, asigna un rol y, si es Domiciliario, agrega placa, vehículo y base de efectivo.)
  4. [Sistema] Crea la cuenta  (El sistema crea la cuenta, encripta la contraseña y la deja activa.)

Decisión (símbolo): ¿Un domiciliario o ayudante intenta registrarse por "Registro Libre"?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "No muestra la opción, está deshabilitada"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Los empleados no requieren validar correos; usan las credenciales entregadas por el administrador (RF-29).
  - La base de efectivo asignada se usa posteriormente en la liquidación del cierre de caja (RF-50).
```

### Página 3 — RF-28: Generación Automática de Contraseña o PIN de Empleado  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-28 - Generación Automática de Contraseña o PIN de Empleado" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Abre el formulario de creación o edición  (El administrador abre el formulario de creación o edición de un empleado y presiona "Generar automáticamente".)
  2. [Sistema] Genera una contraseña  (El sistema genera una contraseña (o PIN, según el rol) aleatoria y segura, y la muestra en el campo correspondiente.)
  3. [Administrador] Guarda el registro del empleado  (El administrador guarda el registro del empleado.)
  4. [Sistema] Encripta  (El sistema encripta y almacena la credencial generada, dejándola lista para ser notificada (RF-29).)

Decisión (símbolo): ¿El administrador prefiere digitar la contraseña manualmente?

  Si la respuesta es "Sí":
    → Actividad nueva: [Administrador] "Desactiva la generación automática y la escribe"  (carril: Administrador)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La contraseña o PIN generado nunca se muestra en texto plano fuera de este formulario ni se almacena sin encriptar.
  - Aplica tanto para Ayudantes de Cocina como para Domiciliarios (RF-27).
```

### Página 4 — RF-29: Notificación de Credenciales al Empleado  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-29 - Notificación de Credenciales al Empleado" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Termina de crear el empleado (RF-27)  (El administrador termina de crear el empleado (RF-27).)
  2. [Sistema] Genera la vista de credenciales  (El sistema genera la vista de credenciales (usuario y contraseña temporal).)
  3. [Administrador] Elige "Copiar" o "Enviar por WhatsApp"  (El administrador elige "Copiar" o "Enviar por WhatsApp".)
  4. [Sistema] Ejecuta la acción correspondiente  (El sistema ejecuta la acción correspondiente y confirma el envío o copiado.)

Decisión (símbolo): ¿El empleado no tiene número de WhatsApp válido registrado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Solo habilita la opción de copiado manual"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Reduce el riesgo de errores de digitación al entregar accesos.
  - La contraseña temporal debe cambiarse en el primer inicio de sesión del empleado.
```

### Página 5 — RF-30: Baja de Empleados  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-30 - Baja de Empleados" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Entra a "Personal"  (El administrador entra a "Personal" y selecciona un ex-empleado.)
  2. [Sistema] Muestra la opción "Dar de baja"  (El sistema muestra la opción "Dar de baja".)
  3. [Administrador] Confirma la baja  (El administrador confirma la baja.)
  4. [Sistema] Revoca el acceso del usuario inmediatamente  (El sistema revoca el acceso del usuario inmediatamente.)

Decisión (símbolo): ¿El empleado tiene un pedido en curso al momento?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Permite finalizar el pedido antes de bloquear"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El bloqueo se maneja mediante "Soft Delete" para mantener el historial de qué empleado preparó o entregó pedidos pasados.
  - Toda baja queda registrada en el log de auditoría (RNF-Trazabilidad).
```

### Página 6 — RF-31: Eliminación Permanente de Registro de Empleado  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-31 - Eliminación Permanente de Registro de Empleado" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Selecciona un empleado inactivo  (El administrador selecciona un empleado inactivo y elige la opción "Eliminar permanentemente".)
  2. [Sistema] Valida si el empleado tiene historial de pedidos  (El sistema valida si el empleado tiene historial de pedidos preparados o entregados asociado.)
  3. [Administrador] Confirma la eliminación en el mensaje de advertencia  (El administrador confirma la eliminación en el mensaje de advertencia.)
  4. [Sistema] Borra el registro de forma definitiva  (El sistema borra el registro de forma definitiva y notifica que la acción no se puede deshacer.)

Decisión (símbolo): ¿El empleado tiene historial de pedidos asociado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Bloquea la eliminación y sugiere dejarlo dado de baja"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Esta acción es irreversible y distinta de la baja de personal (RF-30), la cual sí conserva el historial.
  - Solo el administrador puede ejecutar esta acción; no existe autoeliminación de cuentas.
```

### Página 7 — RF-32: Gestión del Catálogo de Productos con Etiquetas Destacadas  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-32 - Gestión del Catálogo de Productos con Etiquetas Destacadas" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Gestión de Menú"  (El administrador ingresa a "Gestión de Menú" y edita un producto.)
  2. [Sistema] Procesa la modificación  (El sistema procesa la modificación, incluyendo la etiqueta destacada seleccionada.)
  3. [Administrador] Guarda los cambios  (El administrador guarda los cambios.)
  4. [Sistema] Actualiza el registro  (El sistema actualiza el registro y refresca el catálogo del cliente.)

Decisión (símbolo): ¿Un producto está atado a un pedido activo?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Respeta el precio original del carrito ya generado"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - CRUD = Create, Read, Update, Delete.
  - Las categorías utilizadas provienen de la gestión de categorías (RF-35).
```

### Página 8 — RF-33: Ocultamiento Temporal de Producto del Catálogo  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-33 - Ocultamiento Temporal de Producto del Catálogo" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Abre la ficha de un producto  (El administrador abre la ficha de un producto y activa el interruptor "Oculto".)
  2. [Sistema] Cambia el estado del producto  (El sistema cambia el estado del producto y lo retira del catálogo público (RF-07) de inmediato.)
  3. [Administrador] Decide reactivar el producto  (El administrador decide reactivar el producto y desactiva el interruptor.)
  4. [Sistema] Restaura el producto en el catálogo visible conservando  (El sistema restaura el producto en el catálogo visible conservando su receta, precio e historial.)

Decisión (símbolo): ¿El administrador oculta el producto manualmente (a diferencia del bloqueo automático por Agotado, RF-07)?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Oculta el producto por decisión manual"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El historial de ventas del producto oculto se conserva intacto para efectos del dashboard (RF-36) y los reportes.
  - Un producto oculto no puede seleccionarse en el creador interactivo (RF-08) ni en la recompra en 1 clic (RF-09).
```

### Página 9 — RF-34: Configuración de Receta, Costos y Margen de Ganancia (Escandallo)  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-34 - Configuración de Receta, Costos y Margen de Ganancia (Escandallo)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Abre la ficha de "Receta" de un producto  (El administrador abre la ficha de "Receta" de un producto.)
  2. [Sistema] Despliega el listado de insumos  (El sistema despliega el listado de insumos y empaques disponibles en inventario.)
  3. [Administrador] Asigna cada insumo/empaque con su cantidad exacta  (El administrador asigna cada insumo/empaque con su cantidad exacta.)
  4. [Sistema] Calcula automáticamente costo de insumos  (El sistema calcula automáticamente costo de insumos, costo de empaques, costo total, ganancia neta y margen de ganancia.)

Decisión (símbolo): ¿El administrador no ha configurado el precio de venta?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Solicita el precio de venta antes de calcular"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Esta receta es la base del auto-descuento de inventario en cada venta (RF-42).
  - El margen de ganancia por defecto de insumos del creador interactivo se configura en RF-52.
```

### Página 10 — RF-35: Gestión de Categorías del Menú  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-35 - Gestión de Categorías del Menú" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Categorías"  (El administrador ingresa a "Categorías" y crea una nueva.)
  2. [Sistema] Valida que el nombre no esté duplicado  (El sistema valida que el nombre no esté duplicado y la agrega a la lista.)
  3. [Administrador] Elimina una categoría sin productos asociados  (El administrador elimina una categoría sin productos asociados.)
  4. [Sistema] La remueve de la lista disponible  (El sistema la remueve de la lista disponible.)

Decisión (símbolo): ¿El administrador intenta eliminar una categoría que tiene productos?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza la eliminación y pide reasignar productos"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Las categorías se reflejan tanto en el panel de administración como en el catálogo público (RF-07).
  - Ejemplos: Hamburguesas de pan, Hamburguesas de patacón, Perros calientes, Mazorcadas, Salchipapas.
```

### Página 11 — RF-36: Dashboard de Ventas y Productos Más Vendidos  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-36 - Dashboard de Ventas y Productos Más Vendidos" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Navega a la sección de Analítica  (El administrador navega a la sección de Analítica.)
  2. [Sistema] Consolida las transacciones  (El sistema consolida las transacciones y genera gráficos de ventas.)
  3. [Sistema] Calcula  (El sistema calcula y ordena el ranking de productos más vendidos.)

Decisión (símbolo): ¿La base de datos es muy extensa?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra un indicador de carga (Loader)"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Se alimenta de las reseñas del cliente (RF-19) para complementar el análisis de calidad.
```

### Página 12 — RF-37: Filtro de Rango de Tiempo del Dashboard Analítico  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-37 - Filtro de Rango de Tiempo del Dashboard Analítico" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Abre el dashboard de ventas (RF-36)  (El administrador abre el dashboard de ventas (RF-36) y despliega el selector de rango de tiempo.)
  2. [Sistema] Muestra las opciones disponibles: Hoy  (El sistema muestra las opciones disponibles: Hoy, Esta semana, Semana pasada, Este mes, Mes pasado.)
  3. [Administrador] Selecciona uno de los rangos  (El administrador selecciona uno de los rangos.)
  4. [Sistema] Recalcula  (El sistema recalcula y refresca todos los gráficos y KPIs con la información correspondiente a ese periodo.)

Decisión (símbolo): ¿No existen ventas registradas en el rango seleccionado?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra gráficos en cero: "Sin datos""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El rango "Hoy" es el valor seleccionado por defecto al abrir el dashboard.
  - El filtro aplica de forma simultánea a todos los gráficos y KPIs de la pantalla, incluyendo el ranking de productos más vendidos.
```

### Página 13 — RF-38: Consulta de Base de Clientes con Historial de Pedidos  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-38 - Consulta de Base de Clientes con Historial de Pedidos" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a la sección "Clientes"  (El administrador ingresa a la sección "Clientes".)
  2. [Sistema] Despliega el listado de clientes con datos básicos  (El sistema despliega el listado de clientes con datos básicos y puntos acumulados.)
  3. [Administrador] Selecciona un cliente específico  (El administrador selecciona un cliente específico.)
  4. [Sistema] Muestra el historial detallado de todos sus pedidos  (El sistema muestra el historial detallado de todos sus pedidos.)

Decisión (símbolo): ¿No existen clientes registrados aún?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra el listado de clientes vacío"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Los datos provienen directamente del registro de perfil del cliente (RF-02).
  - Fomenta la retención mediante el análisis del comportamiento histórico de compra.
```

### Página 14 — RF-39: Alerta Multisensorial de Nuevo Pedido  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-39 - Alerta Multisensorial de Nuevo Pedido" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Un cliente confirma un pedido exitosamente  (Un cliente confirma un pedido exitosamente.)
  2. [Sistema] El servidor emite un evento en tiempo real  (El servidor emite un evento en tiempo real hacia el panel del administrador.)
  3. [Sistema] El panel del administrador capta el evento  (El panel del administrador capta el evento, reproduce el sonido de alerta y muestra el resumen en pantalla.)

Decisión (símbolo): ¿El navegador del administrador tiene bloqueadas las políticas de autoplay de audio?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Despliega únicamente la alerta visual"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Mantiene sincronizado al administrador con el flujo de cocina sin tener que recargar la página.
  - Requiere una infraestructura en tiempo real eficiente para evitar latencia (RNF-Rendimiento).
```

### Página 15 — RF-40: Panel de Notificaciones del Administrador  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-40 - Panel de Notificaciones del Administrador" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Presiona el ícono de notificaciones  (El administrador presiona el ícono de notificaciones.)
  2. [Sistema] Despliega el panel con los eventos ocurridos durante  (El sistema despliega el panel con los eventos ocurridos durante la sesión, ordenados del más reciente al más antiguo.)
  3. [Administrador] Hace clic sobre una notificación específica  (El administrador hace clic sobre una notificación específica.)
  4. [Sistema] Lo redirige directamente a la pantalla o registro  (El sistema lo redirige directamente a la pantalla o registro relacionado con ese evento.)

Decisión (símbolo): ¿No ha ocurrido ningún evento durante la sesión activa?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra "No hay notificaciones nuevas""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El panel incluye, entre otros: nuevos pedidos (RF-39), alertas de insumos críticos (RF-46), altas o bajas de personal (RF-27, RF-30) y cambios de configuración (tarifa, márgenes, horarios).
  - El historial del panel se reinicia al cerrar sesión; el registro permanente se conserva en el historial de movimientos de inventario (RF-48) y en los reportes de cierre (RF-50).
```

### Página 16 — RF-41: Tablero Consolidado de Órdenes en Tiempo Real  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-41 - Tablero Consolidado de Órdenes en Tiempo Real" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Confirma el pago exitosamente  (El cliente confirma el pago exitosamente.)
  2. [Sistema] Recibe la confirmación  (El sistema recibe la confirmación y ubica la orden en "Pendientes" sin requerir aprobación manual.)
  3. [Administrador] Abre el tablero consolidado  (El administrador abre el tablero consolidado.)
  4. [Sistema] Muestra las órdenes agrupadas por estado  (El sistema muestra las órdenes agrupadas por estado con su detalle completo (cliente, domiciliario, artículos, subtotal, envío y total).)

Decisión (símbolo): ¿La pasarela de pagos reporta un error de fondos?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "No envía la orden a cocina y alerta al cliente"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Reduce la carga operativa del administrador drásticamente, ya que no requiere aprobación manual.
  - Sustituye la revisión pedido por pedido por una vista panorámica del negocio.
```

### Página 17 — RF-42: Visualización del Detalle de Auto-Descuento de Inventario (Insumos y Empaques)  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-42 - Visualización del Detalle de Auto-Descuento de Inventario (Insumos y Empaques)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] El sistema  (El sistema, tras confirmarse una venta, lee la receta (RF-34) de cada producto vendido.)
  2. [Sistema] Resta las unidades exactas de ingredientes  (El sistema resta las unidades exactas de ingredientes y de empaques/desechables del stock global.)
  3. [Administrador] Ingresa al detalle de movimientos de inventario  (El administrador ingresa al detalle de movimientos de inventario.)
  4. [Sistema] Despliega el historial de insumos  (El sistema despliega el historial de insumos y empaques descontados por cada venta.)

Decisión (símbolo): ¿El pedido del cliente incluyó la omisión?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "No descuenta esa porción del inventario"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Interactúa con el Creador Interactivo (RF-08) para bloquear insumos en cero.
  - Da trazabilidad total para el cruce del Cierre de Caja (RF-50) y el historial de movimientos (RF-48).
```

### Página 18 — RF-43: Registro Manual de Pedidos Alternos  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-43 - Registro Manual de Pedidos Alternos" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Atiende la llamada e ingresa los datos  (El administrador atiende la llamada e ingresa los datos del cliente y su pedido.)
  2. [Sistema] Calcula el total incluyendo la tarifa plana  (El sistema calcula el total incluyendo la tarifa plana de envío.)
  3. [Administrador] Presiona "Enviar a Cocina"  (El administrador presiona "Enviar a Cocina".)
  4. [Sistema] Descuenta el inventario  (El sistema descuenta el inventario y manda el pedido a la pantalla del ayudante (KDS), generando su tirilla igual que un pedido web.)

Decisión (símbolo): ¿El administrador intenta ingresar un producto que acaba?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra una alerta visual de producto sin stock"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Permite registrar ventas en efectivo (contra entrega) bajo el control absoluto del admin.
  - Unifica todas las ventas (web y manuales) para el reporte de caja (RF-50).
```

### Página 19 — RF-44: Corrección de Dirección en Curso  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-44 - Corrección de Dirección en Curso" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Avisa por chat/llamada que su dirección está mal  (El cliente avisa por chat/llamada que su dirección está mal.)
  2. [Administrador] Busca el pedido  (El administrador busca el pedido y edita el campo de dirección.)
  3. [Sistema] Guarda el cambio  (El sistema guarda el cambio y envía una alerta a la app de logística.)
  4. [Sistema] Recalcula la ruta en el mapa del domiciliario  (El sistema recalcula la ruta en el mapa del domiciliario (RF-67).)

Decisión (símbolo): ¿El pedido ya fue marcado como "Entregado"?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Deshabilita el botón de corrección de dirección"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Requiere sincronización en tiempo real con la app de última milla.
  - Evita entregas fallidas y mermas por comida devuelta.
```

### Página 20 — RF-45: Abastecimiento Express de Inventario  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-45 - Abastecimiento Express de Inventario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Abastecimiento Express"  (El administrador ingresa a "Abastecimiento Express".)
  2. [Sistema] Lista los insumos agrupados por categoría  (El sistema lista los insumos agrupados por categoría, incluyendo "Empaques & Desechables".)
  3. [Administrador] Digita cantidad  (El administrador digita cantidad, costo total y proveedor (opcional) y presiona "+".)
  4. [Sistema] Suma la cantidad al stock anterior  (El sistema suma la cantidad al stock anterior, actualiza el costo y guarda el registro.)

Decisión (símbolo): ¿El administrador intenta sumar un valor negativo o texto?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza la entrada y exige un número válido"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Si un insumo estaba "Agotado", esta acción lo desbloquea instantáneamente en el módulo del cliente (RF-07).
  - Cada movimiento queda registrado en el historial de inventario (RF-48).
```

### Página 21 — RF-46: Alerta de Insumos en Estado Crítico  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-46 - Alerta de Insumos en Estado Crítico" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Evalúa periódicamente el nivel de cada insumo  (El sistema evalúa periódicamente el nivel de cada insumo.)
  2. [Sistema] Detecta un insumo con existencias iguales o menores  (El sistema detecta un insumo con existencias iguales o menores al umbral (10 unidades por defecto).)
  3. [Sistema] Despliega la alerta visual  (El sistema despliega la alerta visual y sonora en el panel del administrador.)

Decisión (símbolo): ¿El administrador realiza un abastecimiento express (RF-45) que supere?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Retira automáticamente la alerta de ese insumo"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - El umbral de 10 unidades es el valor por defecto y podría parametrizarse a futuro.
  - Complementa la alerta visible para el ayudante de cocina (RF-60).
```

### Página 22 — RF-47: Consulta de Valorización Monetaria del Inventario  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-47 - Consulta de Valorización Monetaria del Inventario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Valorización de Inventario"  (El administrador ingresa a "Valorización de Inventario".)
  2. [Sistema] Multiplica la cantidad disponible de cada insumo  (El sistema multiplica la cantidad disponible de cada insumo por su costo promedio.)
  3. [Sistema] Suma todos los subtotales  (El sistema suma todos los subtotales y despliega el valor monetario total del inventario.)

Decisión (símbolo): ¿Algún insumo no tiene costo registrado aún?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Lo excluye y lo marca "Pendiente de costeo""  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Incluye tanto ingredientes como empaques/desechables.
  - Sirve de insumo para el cruce del cierre de caja (RF-50).
```

### Página 23 — RF-48: Historial de Movimientos de Inventario  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-48 - Historial de Movimientos de Inventario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa al historial de inventario  (El administrador ingresa al historial de inventario.)
  2. [Sistema] Consulta todos los movimientos registrados  (El sistema consulta todos los movimientos registrados (abastecimientos y auto-descuentos).)
  3. [Administrador] Aplica un filtro de fecha o insumo  (El administrador aplica un filtro de fecha o insumo.)
  4. [Sistema] Despliega el listado filtrado en orden cronológico  (El sistema despliega el listado filtrado en orden cronológico.)

Decisión (símbolo): ¿No hay movimientos registrados en el rango de fechas?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra el listado de movimientos vacío"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Se alimenta del abastecimiento express (RF-45) y del auto-descuento por ventas (RF-42).
  - Es la base del log de auditoría del inventario (RNF-Trazabilidad).
```

### Página 24 — RF-49: Mapa GPS en Tiempo Real de Domiciliarios  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-49 - Mapa GPS en Tiempo Real de Domiciliarios" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Mapa de Logística"  (El administrador ingresa a "Mapa de Logística".)
  2. [Sistema] Consulta la posición GPS de todos los domiciliarios  (El sistema consulta la posición GPS de todos los domiciliarios con sesión activa.)
  3. [Sistema] Despliega un marcador por domiciliario con su vehículo  (El sistema despliega un marcador por domiciliario con su vehículo, placa, estado (RF-65) y pedido asignado.)

Decisión (símbolo): ¿Un domiciliario pierde la señal GPS?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Muestra la última posición conocida y su hora"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - La placa y el tipo de vehículo provienen del registro del empleado (RF-27).
  - Complementa la corrección de direcciones en curso (RF-44).
```

### Página 25 — RF-50: Generación de Reporte de Cierre de Caja con Liquidación por Domiciliario  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-50 - Generación de Reporte de Cierre de Caja con Liquidación por Domiciliario" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Presiona "Generar Cierre de Caja"  (El administrador presiona "Generar Cierre de Caja".)
  2. [Sistema] Suma todas las transacciones exitosas del día  (El sistema suma todas las transacciones exitosas del día, agrupadas por pago digital y efectivo.)
  3. [Sistema] Cruza el consumo contra el auto-descuento de inventario  (El sistema cruza el consumo contra el auto-descuento de inventario (RF-42) y calcula la liquidación de cada domiciliario frente a su base asignada (RF-27).)
  4. [Administrador] Presiona "Exportar a PDF"  (El administrador presiona "Exportar a PDF".)
  5. [Sistema] Genera el documento descargable con el balance completo  (El sistema genera el documento descargable con el balance completo.)

Decisión (símbolo): ¿Aún hay pedidos en curso?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Advierte que el cierre será parcial"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 5), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Ahorra tiempo administrativo vital al final de la jornada.
  - Sirve de base para auditorías financieras y queda registrado en el log de trazabilidad.
```

### Página 26 — RF-51: Configuración de Tarifa Plana de Domicilio  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-51 - Configuración de Tarifa Plana de Domicilio" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Ajustes de Sistema"  (El administrador ingresa a "Ajustes de Sistema".)
  2. [Sistema] Despliega el campo actual de la tarifa  (El sistema despliega el campo actual de la tarifa de envío.)
  3. [Administrador] Cambia el valor  (El administrador cambia el valor y presiona "Guardar".)
  4. [Sistema] Valida  (El sistema valida y aplica el nuevo valor a los próximos carritos.)

Decisión (símbolo): ¿Se deja el campo en blanco?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Pide un valor válido (puede ser "0")"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Impacta directamente el RF-14 (módulo del cliente).
  - No afecta el historial de pedidos anteriores.
```

### Página 27 — RF-52: Configuración del Margen de Ganancia por Defecto del Creador Interactivo  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-52 - Configuración del Margen de Ganancia por Defecto del Creador Interactivo" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Ingresa a "Configuración del Creador Interactivo"  (El administrador ingresa a "Configuración del Creador Interactivo".)
  2. [Sistema] Despliega el porcentaje de margen actual  (El sistema despliega el porcentaje de margen actual.)
  3. [Administrador] Cambia el porcentaje  (El administrador cambia el porcentaje y presiona "Guardar".)
  4. [Sistema] Recalcula el precio de cada insumo disponible  (El sistema recalcula el precio de cada insumo disponible en el minijuego con el nuevo margen.)

Decisión (símbolo): ¿El administrador ingresa un valor negativo o mayor?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Rechaza el cambio y solicita un valor válido"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 4), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Este margen es independiente del margen calculado por producto en el escandallo (RF-34).
  - Evita que el cliente arme combinaciones no rentables para el negocio.
```

### Página 28 — RF-53: Pausa de Emergencia (Botón de Pánico)  `[Módulo: Administrador]`

```
Crea el DIAGRAMA DE ACTIVIDADES (UML) de "RF-53 - Pausa de Emergencia (Botón de Pánico)" en una página nueva, siguiendo el estilo del Prompt Maestro. NO me hagas preguntas de aclaración: toda la información que necesitas ya está en esta instrucción; si tienes alguna duda de interpretación, sigue la REGLA FIJA indicada más abajo sin detenerte a preguntar.
Carriles: "Administrador" | "Sistema"

Secuencia normal (Inicio → pasos → decisión/Fin). Texto en el símbolo = CORTO; entre paréntesis = solo referencia, NO dibujar:
  1. [Administrador] Detecta un imprevisto (ej. se acabó el gas)  (El administrador detecta un imprevisto (ej. se acabó el gas) y presiona el botón de pánico.)
  2. [Sistema] Activa el estado "Pausa de Emergencia" a nivel  (El sistema activa el estado "Pausa de Emergencia" a nivel global.)
  3. [Sistema] Bloquea el botón "Pagar" en el módulo cliente  (El sistema bloquea el botón "Pagar" en el módulo cliente mostrando el aviso correspondiente.)

Decisión (símbolo): ¿El administrador desactiva manualmente la pausa de emergencia?

  Si la respuesta es "Sí":
    → Actividad nueva: [Sistema] "Reanuda la recepción de pedidos de inmediato"  (carril: Sistema)
    → esa actividad conecta directo a Fin. NO dibujes ningún paso adicional en esta rama.

  Si la respuesta es "No":
    → El flujo continúa exactamente con el/los paso(s) que aún no se han dibujado de la Secuencia Normal de arriba, en el mismo orden en que están numerados, cada uno en su carril correspondiente.
    → Al terminar el último paso (paso 3), conecta a Fin.
    → Si ya se dibujaron TODOS los pasos de la Secuencia Normal antes de llegar a este rombo, entonces "No" conecta directo a Fin, sin actividades adicionales.

REGLA FIJA (no te desvíes de esto, no preguntes nada): la rama "Sí" SIEMPRE termina en la actividad nueva indicada arriba y de ahí a Fin. La rama "No" SIEMPRE es la que continúa el proceso con los pasos restantes ya numerados (o va directa a Fin si no quedan pasos). Nunca actives la actividad nueva en la rama "No", y nunca conectes la rama "Sí" a los pasos restantes.

Nota (detalles):
  - Es un interruptor de emergencia, no un cambio permanente del horario de atención.
  - Debe quedar registrado en el log de auditoría (RNF-Trazabilidad).
```
