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
   Envia un formulario POST a la ruta limpia  /admin/inventario/insumo

2. FRONT CONTROLLER (public/index.php)
   - Carga $conn (config/database.php), helpers.php, Session, Auth y los 11 modelos
   - Normaliza la URL  ->  $uri = '/admin/inventario/insumo'
   - GUARDIA RBAC: la ruta empieza por /admin  ->  exige rol 'admin'
   - Ve que termina en '/insumo'  ->  $_POST['action'] = 'guardarInsumo'
   - require app/Controllers/Admin/InventarioController.php

3. CONTROLADOR (app/Controllers/Admin/InventarioController.php)
   - Lee $action = $_GET['action'] ?? $_POST['action'] ?? ''
   - Entra en la rama  if ($action === 'guardarInsumo')
   - Valida a mano lo que llega en $_POST
   - Instancia el modelo  (new Ingrediente())  y le pide guardar

4. MODELO (app/Models/Ingrediente.php)
   - Usa $this->conn (la instancia global de mysqli, inyectada en el constructor)
   - Prepara la consulta con marcadores ?   ($this->conn->prepare)
   - Vincula los valores  ($stmt->bind_param)  y ejecuta  ($stmt->execute)

5. CONTROLADOR
   - Deja una notificacion en la sesion ($_SESSION['_flash'])
   - Redirecciona:  redirect('/admin/inventario')          <- patron PRG

6. NAVEGADOR  ->  GET /admin/inventario
   - Vuelve a pasar por public/index.php, ahora por el switch de vistas:
     consulta los modelos, captura la vista con ob_start() y la mete en el layout
   - toast.php pinta la notificacion que quedo en la sesion
```

> Fíjate en el paso 2: **la ruta no nombra el archivo del controlador**. Los formularios
> apuntan a rutas limpias (`/admin/inventario/insumo`) y es `public/index.php` quien
> decide qué script cargar y con qué `$action`.

---

## Diagrama corto

```
              +-----------------------------+
  Peticion    |     public/index.php        |   Front Controller
  ----------->|  $conn + Core + 11 modelos  |   (config/database.php da el $conn global)
              |  guardia RBAC + PIN estacion|
              +--------------+--------------+
                             |
              POST / AJAX    |    GET (pantalla)
          +------------------+------------------+
          v                                     v
 +--------------------+              +-------------------------+
 | Controlador Script |              | switch ($uri) en el     |
 | app/Controllers/.. |              | propio index.php        |
 +---------+----------+              +-----------+-------------+
           |   pide datos                        |  pide datos
           v                                     v
 +------------------------------------------------------------+
 |            Clase Modelo (app/Models/*.php)                  | <--> MySQL
 |            $this->conn->prepare / bind_param / execute      |      (mysqli)
 +------------------------------------------------------------+
           |                                     |
           | redirect() + flash                  v
           |                          +-------------------------+
           |                          | Vista PHP + HTML        |
           |                          | ob_start() -> $content  |
           |                          | + layout                |
           |                          +-----------+-------------+
           v                                      v
     Nueva peticion GET  ----------------->   HTML al navegador
```

---

## ¿Por qué separar así?

- **Cambiar el diseño** (Vista) sin tocar la lógica ni las consultas.
- **Reusar consultas** (Modelo) en varios scripts. Ej: `$pedidoModel->codigo($p)` se usa
  en el admin, la cocina y el domiciliario.
- **Probar y entender** cada pieza por separado.
- **Código directo y tradicional**: Sin complejidad innecesaria de motores de plantillas o enrutadores de framework.

