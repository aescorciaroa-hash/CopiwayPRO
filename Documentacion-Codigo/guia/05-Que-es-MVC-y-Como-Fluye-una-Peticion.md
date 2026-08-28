# 05 · Qué es MVC y cómo fluye una petición

## MVC en una frase

**MVC = Modelo · Vista · Controlador.** Es una forma de organizar el código separando
tres responsabilidades para que no se mezclen.

| Parte | Responsabilidad | En Copiway |
|-------|-----------------|------------|
| **Modelo** | Los **datos** y las reglas de negocio. Habla con MySQL usando `mysqli`. | `app/Models/` |
| **Vista** | La **presentación**: el HTML que ve el usuario. | `app/Views/` |
| **Controlador** | El **coordinador**: script plano que recibe la petición, consulta modelos y redirige o renderiza. | `app/Controllers/` |

**Analogía del restaurante:**
- El **cliente** (navegador) pide un plato (una URL / formulario).
- El **mesero** (Controlador) toma la orden (`$_POST`/`$_GET`), pide a la cocina y devuelve la respuesta.
- La **cocina/despensa** (Modelo) consulta la base de datos `$conn`.
- El **plato emplatado** (Vista) es lo que se muestra en pantalla.

---

## El recorrido completo de una petición

Ejemplo: una acción de guardar insumo en `admin/inventario`

```
1. NAVEGADOR / USUARIO
   Envia un formulario POST a /app/Controllers/Admin/InventarioController.php?action=guardarInsumo

2. public/index.php o controlador directo
   - Carga la conexión global $conn desde config/database.php
   - Inicia las funciones auxiliares helpers.php y la sesión

3. CONTROLADOR (app/Controllers/Admin/InventarioController.php)
   - Valida la acción solicitada ($action === 'guardarInsumo')
   - Instancia el modelo required (new Ingrediente())
   - Llama al método de inserción en la base de datos

4. MODELO (app/Models/Ingrediente.php)
   - Accede a global $conn (instancia de mysqli)
   - Ejecuta la consulta con sentencia preparada ($this->conn->prepare)
   - Asigna los parámetros con $stmt->bind_param(...) y ejecuta con $stmt->execute()

5. CONTROLADOR
   - Establece una notificación de éxito en la sesión ($_SESSION['_flash'])
   - Redirecciona con redirect('/app/Views/admin/inventario/index.php')

6. NAVEGADOR
   Recarga la vista actualizada mostrando la notificación flotante (toast.php).
```

---

## Diagrama corto

```
          ┌─────────────────────┐
Petición  │  public/index.php   │  (Inicialización directa)
─────────▶│ config/database.php │  ($conn global de MySQLi)
          └──────────┬──────────┘
                     ▼
          ┌─────────────────────┐        ┌─────────────────────┐
          │  Controlador Script │◀──────▶│    Clase Modelo     │◀──────▶  MySQL (mysqli)
          └──────────┬──────────┘  datos └─────────────────────┘
                     ▼
          ┌─────────────────────┐
          │   Vista PHP + HTML  │  (Layouts e inclusiones simples)
          └──────────┬──────────┘
                     ▼
             Respuesta  ──────────────────────────────────────▶  Navegador
```

---

## ¿Por qué separar así?

- **Cambiar el diseño** (Vista) sin tocar la lógica ni las consultas.
- **Reusar consultas** (Modelo) en varios scripts. Ej: `$pedidoModel->codigo($p)` se usa
  en el admin, la cocina y el domiciliario.
- **Probar y entender** cada pieza por separado.
- **Código directo y tradicional**: Sin complejidad innecesaria de motores de plantillas o enrutadores de framework.

