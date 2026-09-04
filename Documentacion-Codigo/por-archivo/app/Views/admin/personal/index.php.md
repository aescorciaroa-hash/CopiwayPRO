# `app/Views/admin/personal/index.php`

## Ubicación
`app/Views/admin/personal/index.php`

## Propósito
**Equipo y Personal**: la lista de ayudantes de cocina y domiciliarios, con alta, edición,
baja, reactivación y eliminación, más el envío de credenciales.

Cubre **RF-27** a **RF-31**.

## Quién la renderiza
`public/index.php`, `case '/admin/personal'`, con el layout **`admin`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$empleados` | `Empleado::todos()` — las dos tablas unidas, cada fila con su `rol` |
| `$activos` | `Empleado::activos()` (un entero, para el KPI) |

## Acciones por empleado

Todas llevan el `{rol}` en la ruta, porque el controlador necesita saber en qué tabla
buscar:

| Botón | Ruta |
|---|---|
| Editar | `fetch` a `/admin/personal/{rol}/{id}` (acción `cargar`) |
| Dar de baja | `POST /admin/personal/{rol}/{id}/baja` — **soft delete**, `activo = 0` |
| Reactivar | `POST /admin/personal/{rol}/{id}/reactivar` |
| Eliminar | `POST /admin/personal/{rol}/{id}/eliminar` |

> **Baja ≠ Eliminar.** La baja conserva el historial y los reportes; eliminar solo funciona
> si el empleado nunca tuvo pedidos (`Empleado::eliminar()` devuelve `false` si los tiene y
> el controlador se limita a avisar).

## El alta (`nuevoOpen`)

Un modal con `x-data="{ rol: 'cocina', pwd: '' }"`: al elegir "domiciliario" aparecen los
campos propios (`tipo_vehiculo`, `placa`, `base_efectivo_asignada`).

`POST` a `/admin/personal`. El controlador rechaza correos o teléfonos duplicados
consultando **las cuatro** tablas de cuentas (`Usuario::existeCorreoOTelefono`).

## El componente `personalPage()` — entrega de credenciales (RF-29)

Además de `editar(rol, id)`, tiene dos ayudas para hacerle llegar los accesos al empleado:

```js
get waLink() {
    const t = `Hola ${this.form.nombre}, tus accesos Copiway: usuario ${this.form.correo}` +
              (this.form.nueva_pwd ? `, clave ${this.form.nueva_pwd}` : '');
    return 'https://wa.me/?text=' + encodeURIComponent(t);
},
copiar() {
    navigator.clipboard.writeText(`Usuario: ${this.form.correo}\nClave: ${this.form.nueva_pwd || '(sin cambios)'}`);
    window.toast('staff', 'Copiado', 'Credenciales copiadas al portapapeles.');
}
```

- **WhatsApp** — abre `wa.me` con el mensaje ya escrito. Sin número: el admin elige el
  contacto.
- **Copiar** — al portapapeles, y avisa con un toast (usando el `window.toast` que publica
  `partials/toast.php`).

> Las credenciales viajan **en claro** por WhatsApp o el portapapeles. Es lo que pide
> RF-29, pero conviene saberlo: la contraseña solo es legible en ese momento, porque en la
> base de datos se guarda como hash bcrypt y no se puede recuperar.

## Notas
- El `@keydown.escape.window="nuevoOpen = false"` cierra el modal con la tecla Escape.
- `editar()` recibe el empleado **sin** el campo `contrasena`: el controlador lo elimina
  antes de responder el JSON.
