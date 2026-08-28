# 07 · Procesamiento de Peticiones y Control de Acceso

## Arquitectura de Procesamiento Directo

En la versión simplificada del proyecto, las peticiones HTTP y acciones de formulario se envían directamente a los scripts procesadores de controladores (`app/Controllers/...`) pasando un parámetro `action` vía `GET` o `POST`.

### Ejemplo de Procesamiento de Formulario:
```php
<form method="POST" action="/app/Controllers/AuthController.php?action=login">
    <!-- inputs -->
    <button type="submit">Ingresar</button>
</form>
```

Y en el controlador script ([AuthController.php](file:///c:/Users/ANDRES%20CAMILO/Downloads/Copiway/app/Controllers/AuthController.php)):
```php
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'login') {
    // Procesa el formulario, valida credenciales
    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Bienvenido', 'message' => 'Sesión iniciada.'];
    redirect('/app/Views/admin/dashboard.php');
}
```

---

## Control de Acceso por Sesión

El acceso a las vistas protegidas por rol se valida verificando la sesión activa al inicio de la vista o del script correspondiente usando los métodos de `Auth`:

```php
if (!Auth::check() || Auth::role() !== 'admin') {
    redirect('/app/Views/auth/login.php');
}
```

| Módulo / Rol | Vistas y Controladores Asociados |
| :--- | :--- |
| **Cliente** (`cliente`) | `app/Views/client/`, `app/Controllers/Client/` |
| **Cocina** (`cocina`) | `app/Views/kitchen/`, `app/Controllers/Kitchen/` |
| **Domiciliario** (`domiciliario`) | `app/Views/delivery/`, `app/Controllers/Delivery/` |
| **Administrador** (`admin`) | `app/Views/admin/`, `app/Controllers/Admin/` |

---

## Patrón POST → Redirect (PRG)

Todos los procesadores de peticiones `POST` finalizan su ejecución invocando la función `redirect(...)` en lugar de imprimir HTML directamente.

Esto implementa el patrón **Post-Redirect-Get (PRG)**, el cual evita que el navegador reenvíe accidentalmente el formulario al presionar recargar (F5) o duplicar operaciones sensibles como cobros o creación de pedidos.

