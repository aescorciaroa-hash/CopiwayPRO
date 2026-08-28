<?php
/**
 * Punto de entrada principal y enrutador simple.
 */
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/Core/helpers.php';
require_once dirname(__DIR__) . '/app/Core/Session.php';
require_once dirname(__DIR__) . '/app/Core/Auth.php';
require_once dirname(__DIR__) . '/app/Core/Periodo.php';

// Cargar todos los modelos
require_once dirname(__DIR__) . '/app/Models/Usuario.php';
require_once dirname(__DIR__) . '/app/Models/Cliente.php';
require_once dirname(__DIR__) . '/app/Models/Empleado.php';
require_once dirname(__DIR__) . '/app/Models/Producto.php';
require_once dirname(__DIR__) . '/app/Models/Categoria.php';
require_once dirname(__DIR__) . '/app/Models/Ingrediente.php';
require_once dirname(__DIR__) . '/app/Models/Pedido.php';
require_once dirname(__DIR__) . '/app/Models/PedidoServicio.php';
require_once dirname(__DIR__) . '/app/Models/Configuracion.php';
require_once dirname(__DIR__) . '/app/Models/CierreCaja.php';
require_once dirname(__DIR__) . '/app/Models/Carrito.php';

Session::start();

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Quitar sufijo /public si se ejecuta en subcarpeta
$uri = preg_replace('#^/public#i', '', $uri);
if (empty($uri)) {
    $uri = '/';
}

// 1. Procesamiento de formularios y acciones de Controladores (POST o con parámetro action)
if ($method === 'POST' || (isset($_GET['action']) && $_GET['action'] !== '')) {
    if ($uri === '/login' || $uri === '/register' || $uri === '/forgot' || $uri === '/logout') {
        require dirname(__DIR__) . '/app/Controllers/AuthController.php';
        exit;
    }
    if (str_starts_with($uri, '/admin/comandas')) {
        if (str_ends_with($uri, '/manual')) $_POST['action'] = 'crearManual';
        elseif (str_contains($uri, '/direccion')) $_POST['action'] = 'editarDireccion';
        require dirname(__DIR__) . '/app/Controllers/Admin/ComandasController.php';
        exit;
    }
    if (str_starts_with($uri, '/admin/menu')) {
        if (str_ends_with($uri, '/producto')) $_POST['action'] = 'guardarProducto';
        elseif (str_contains($uri, '/estado')) $_POST['action'] = 'cambiarEstado';
        elseif (str_contains($uri, '/eliminar') && str_contains($uri, '/producto/')) $_POST['action'] = 'eliminarProducto';
        elseif (str_ends_with($uri, '/categoria')) $_POST['action'] = 'crearCategoria';
        elseif (str_contains($uri, '/eliminar') && str_contains($uri, '/categoria/')) $_POST['action'] = 'eliminarCategoria';
        require dirname(__DIR__) . '/app/Controllers/Admin/MenuController.php';
        exit;
    }
    if (str_starts_with($uri, '/admin/inventario')) {
        if (str_ends_with($uri, '/insumo')) $_POST['action'] = 'guardarInsumo';
        elseif (str_contains($uri, '/ajuste')) $_POST['action'] = 'ajustar';
        require dirname(__DIR__) . '/app/Controllers/Admin/InventarioController.php';
        exit;
    }
    if (str_starts_with($uri, '/admin/personal')) {
        if (str_contains($uri, '/baja')) $_POST['action'] = 'baja';
        elseif (str_contains($uri, '/reactivar')) $_POST['action'] = 'reactivar';
        elseif (str_contains($uri, '/eliminar')) $_POST['action'] = 'eliminar';
        elseif ($uri === '/admin/personal') $_POST['action'] = $_POST['action'] ?? 'crear';
        require dirname(__DIR__) . '/app/Controllers/Admin/PersonalController.php';
        exit;
    }
    if (str_starts_with($uri, '/admin/clientes')) {
        require dirname(__DIR__) . '/app/Controllers/Admin/ClientesController.php';
        exit;
    }
    if (str_starts_with($uri, '/admin/ajustes')) {
        if (str_ends_with($uri, '/tarifa')) $_POST['action'] = 'tarifa';
        elseif (str_ends_with($uri, '/margen')) $_POST['action'] = 'margen';
        elseif (str_ends_with($uri, '/horario')) $_POST['action'] = 'horario';
        elseif (str_ends_with($uri, '/pausa')) $_POST['action'] = 'pausa';
        elseif (str_ends_with($uri, '/cierre')) $_POST['action'] = 'generarCierre';
        require dirname(__DIR__) . '/app/Controllers/Admin/AjustesController.php';
        exit;
    }
    if (str_starts_with($uri, '/client/carrito')) {
        if (str_ends_with($uri, '/agregar')) $_POST['action'] = 'agregar';
        elseif (str_ends_with($uri, '/quitar')) $_POST['action'] = 'quitar';
        elseif (str_ends_with($uri, '/actualizar')) $_POST['action'] = 'actualizar';
        elseif (str_ends_with($uri, '/vaciar')) $_POST['action'] = 'vaciar';
        require dirname(__DIR__) . '/app/Controllers/Client/CarritoController.php';
        exit;
    }
    if (str_starts_with($uri, '/client/checkout')) {
        require dirname(__DIR__) . '/app/Controllers/Client/CheckoutController.php';
        exit;
    }
    if (str_starts_with($uri, '/client/creador')) {
        if (str_ends_with($uri, '/agregar')) $_POST['action'] = 'agregar';
        require dirname(__DIR__) . '/app/Controllers/Client/CreadorController.php';
        exit;
    }
    if (str_starts_with($uri, '/client/perfil')) {
        if (str_ends_with($uri, '/password')) $_POST['action'] = 'password';
        else $_POST['action'] = $_POST['action'] ?? 'actualizar';
        require dirname(__DIR__) . '/app/Controllers/Client/PerfilController.php';
        exit;
    }
    if (str_starts_with($uri, '/client/historial')) {
        if (str_contains($uri, '/recomprar')) $_POST['action'] = 'recomprar';
        elseif (str_contains($uri, '/resena')) $_POST['action'] = 'resena';
        require dirname(__DIR__) . '/app/Controllers/Client/HistorialController.php';
        exit;
    }
    if ($uri === '/kitchen/estacion' || str_starts_with($uri, '/kitchen/pedido/')) {
        require dirname(__DIR__) . '/app/Controllers/Kitchen/KdsController.php';
        exit;
    }
    if ($uri === '/delivery/estacion' || $uri === '/delivery/disponibilidad' || str_starts_with($uri, '/delivery/pedido/')) {
        require dirname(__DIR__) . '/app/Controllers/Delivery/PanelController.php';
        exit;
    }
}

// 2. Cierre de sesión por GET
if ($uri === '/logout') {
    Auth::logout();
    Session::flash('warning', 'Sesión cerrada', 'Has salido de tu cuenta.');
    redirect('/login');
}

// 3. Enrutamiento de vistas GET
switch ($uri) {
    // ----------------------------------------------------
    // AUTH VIEWS
    // ----------------------------------------------------
    case '/login':
    case '/app/Views/auth/login.php':
        ob_start();
        require dirname(__DIR__) . '/app/Views/auth/login.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/auth.php';
        exit;

    case '/register':
    case '/app/Views/auth/register.php':
        ob_start();
        require dirname(__DIR__) . '/app/Views/auth/register.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/auth.php';
        exit;

    case '/forgot-password':
    case '/forgot':
    case '/app/Views/auth/forgot.php':
        ob_start();
        require dirname(__DIR__) . '/app/Views/auth/forgot.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/auth.php';
        exit;

    // ----------------------------------------------------
    // KITCHEN VIEWS
    // ----------------------------------------------------
    case '/kitchen/estacion':
    case '/app/Views/kitchen/estacion.php':
        require dirname(__DIR__) . '/app/Views/kitchen/estacion.php';
        exit;

    case '/kitchen':
    case '/kitchen/index':
    case '/app/Views/kitchen/index.php':
        $pedidoModel = new Pedido();
        $ingredienteModel = new Ingrediente();
        $activos = $pedidoModel->activos();
        $tablero = ['pendiente' => [], 'en_preparacion' => [], 'listo' => []];
        foreach ($activos as $p) {
            if (isset($tablero[$p['estado']])) {
                $p['codigo'] = $pedidoModel->codigo($p);
                $p['lineas'] = $pedidoModel->detalle($p['id_pedido']);
                $tablero[$p['estado']][] = $p;
            }
        }
        $resumen = [];
        $criticos = $ingredienteModel->criticos();

        ob_start();
        require dirname(__DIR__) . '/app/Views/kitchen/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/kitchen.php';
        exit;

    // ----------------------------------------------------
    // DELIVERY VIEWS
    // ----------------------------------------------------
    case '/delivery/estacion':
    case '/app/Views/delivery/estacion.php':
        require dirname(__DIR__) . '/app/Views/delivery/estacion.php';
        exit;

    case '/delivery':
    case '/delivery/index':
    case '/app/Views/delivery/index.php':
        $pedidoModel = new Pedido();
        $yo = Auth::user() ?? [];
        $activos = $pedidoModel->activos();
        $disponibles = [];
        $mios = [];
        foreach ($activos as $p) {
            $p['codigo'] = $pedidoModel->codigo($p);
            $p['lineas'] = $pedidoModel->detalle($p['id_pedido']);
            if ($p['estado'] === 'listo' && empty($p['id_domiciliario'])) {
                $disponibles[] = $p;
            } elseif (!empty($yo['id']) && ($p['id_domiciliario'] ?? '') === $yo['id']) {
                $mios[] = $p;
            }
        }

        ob_start();
        require dirname(__DIR__) . '/app/Views/delivery/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/delivery.php';
        exit;

    // ----------------------------------------------------
    // ADMIN VIEWS
    // ----------------------------------------------------
    case '/admin':
    case '/admin/dashboard':
    case '/app/Views/admin/dashboard.php':
    case '/app/Views/admin/index.php':
        $periodoKey = Periodo::valida($_GET['periodo'] ?? 'semana');
        $rango = Periodo::rango($periodoKey);
        $rangoHoy = Periodo::rango('hoy');

        $pedidoModel = new Pedido();
        $empleadoModel = new Empleado();
        $configModel = new Configuracion();

        $kpi = $pedidoModel->kpis($rango[0], $rango[1]);
        $kpiHoy = $pedidoModel->kpis($rangoHoy[0], $rangoHoy[1]);
        $ordenesHoy = $kpiHoy['ordenes'];
        $ventasDia = $pedidoModel->ventasPorDia($rango[0], $rango[1]);
        $ranking = $pedidoModel->rankingProductos($rango[0], $rango[1]);
        $recientes = $pedidoModel->recientes(8);
        $contadores = $pedidoModel->contarPorEstado();
        $empleados = $empleadoModel->activos();
        $estadoCocina = $configModel->estadoCocina();
        $periodo = $periodoKey;
        $opciones = Periodo::OPCIONES;

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/dashboard.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/comandas':
    case '/admin/comandas/index':
    case '/app/Views/admin/comandas/index.php':
        $pedidoModel = new Pedido();
        $productoModel = new Producto();
        $activos = $pedidoModel->activos();
        foreach ($activos as &$p) {
            $p['codigo'] = $pedidoModel->codigo($p);
            $p['lineas'] = $pedidoModel->detalle($p['id_pedido']);
        }
        $contadores = $pedidoModel->contarPorEstado();
        $productos = $productoModel->catalogo();

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/comandas/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/rutas':
    case '/admin/rutas/index':
    case '/app/Views/admin/rutas/index.php':
        $pedidoModel = new Pedido();
        $empleadoModel = new Empleado();
        $activos = $pedidoModel->activos();
        foreach ($activos as &$p) {
            $p['codigo'] = $pedidoModel->codigo($p);
        }
        $contadores = $pedidoModel->contarPorEstado();
        $flota = array_values(array_filter($empleadoModel->todos(), fn($e) => $e['rol'] === 'domiciliario'));
        $despachos = array_values(array_filter($activos, fn($p) => $p['estado'] === 'en_camino'));
        $tarifa = (float) Configuracion::value('tarifa_plana_domicilio', 6000);

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/rutas/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/menu':
    case '/admin/menu/index':
    case '/app/Views/admin/menu/index.php':
        $productoModel = new Producto();
        $categoriaModel = new Categoria();
        $ingredienteModel = new Ingrediente();
        $productos = $productoModel->catalogo();
        $categorias = $categoriaModel->menu();
        $ingredientes = $ingredienteModel->conCategoria();

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/menu/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/inventario':
    case '/admin/inventario/index':
    case '/app/Views/admin/inventario/index.php':
        $ingredienteModel = new Ingrediente();
        $categoriaModel = new Categoria();
        $insumos = $ingredienteModel->conCategoria($_GET['buscar'] ?? null);
        $criticos = $ingredienteModel->criticos();
        $categorias = $categoriaModel->deInsumos();
        $kpis = $ingredienteModel->kpis();
        $movimientos = $ingredienteModel->movimientos();

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/inventario/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/personal':
    case '/admin/personal/index':
    case '/app/Views/admin/personal/index.php':
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->todos();
        $activos = $empleadoModel->activos();

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/personal/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/clientes':
    case '/admin/clientes/index':
    case '/app/Views/admin/clientes/index.php':
        $clienteModel = new Cliente();
        $buscar = trim($_GET['q'] ?? '');
        $clientes = $clienteModel->directorio($buscar);

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/clientes/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    case '/admin/ajustes':
    case '/admin/ajustes/index':
    case '/app/Views/admin/ajustes/index.php':
        $configModel = new Configuracion();
        $cierreModel = new CierreCaja();
        $config = $configModel->get();
        $cierre = $cierreModel->obtener(date('Y-m-d'));
        $cierresRecientes = $cierreModel->recientes();

        ob_start();
        require dirname(__DIR__) . '/app/Views/admin/ajustes/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/admin.php';
        exit;

    // ----------------------------------------------------
    // CLIENT VIEWS
    // ----------------------------------------------------
    case '/client':
    case '/client/catalogo':
    case '/app/Views/client/catalogo.php':
        $productoModel  = new Producto();
        $categoriaModel = new Categoria();
        $productos  = $productoModel->catalogo();
        $categorias = $categoriaModel->menu();

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/catalogo.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    case '/client/carrito':
    case '/app/Views/client/carrito.php':
        $carritoModel = new Carrito();
        $yo = Auth::user() ?? [];
        $carrito = !empty($yo['id_cliente']) ? $carritoModel->obtener($yo['id_cliente']) : [];

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/carrito.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    case '/client/checkout':
    case '/app/Views/client/checkout.php':
        $carritoModel = new Carrito();
        $yo = Auth::user() ?? [];
        $carrito = !empty($yo['id_cliente']) ? $carritoModel->obtener($yo['id_cliente']) : [];
        $tarifaPlana = (float) Configuracion::value('tarifa_plana_domicilio', 6000);

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/checkout.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    case '/client/creador':
    case '/app/Views/client/creador.php':
        $ingredienteModel = new Ingrediente();
        $insumos = $ingredienteModel->conCategoria();
        $bases = array_filter($insumos, fn($i) => ($i['categoria'] ?? '') === 'Bases / Panes');
        $extras = array_filter($insumos, fn($i) => ($i['categoria'] ?? '') !== 'Bases / Panes');

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/creador.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    case '/client/historial':
    case '/app/Views/client/historial.php':
        $clienteModel = new Cliente();
        $pedidoModel = new Pedido();
        $yo = Auth::user() ?? [];
        $pedidos = !empty($yo['id_cliente']) ? $clienteModel->historial($yo['id_cliente']) : [];
        foreach ($pedidos as &$p) {
            $p['codigo'] = $pedidoModel->codigo($p);
        }

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/historial.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    case '/client/ordenes':
    case '/app/Views/client/ordenes.php':
        $clienteModel = new Cliente();
        $pedidoModel = new Pedido();
        $yo = Auth::user() ?? [];
        $activos = !empty($yo['id_cliente']) ? $clienteModel->pedidosActivos($yo['id_cliente']) : [];
        foreach ($activos as &$p) {
            $p['codigo'] = $pedidoModel->codigo($p);
            $p['lineas'] = $pedidoModel->detalle($p['id_pedido']);
        }

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/ordenes.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    case '/client/perfil':
    case '/app/Views/client/perfil.php':
        $yo = Auth::user() ?? [];

        ob_start();
        require dirname(__DIR__) . '/app/Views/client/perfil.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/client.php';
        exit;

    // ----------------------------------------------------
    // LANDING / DEFAULT
    // ----------------------------------------------------
    case '/':
    case '/home':
    case '/index.php':
    default:
        $productoModel  = new Producto();
        $categoriaModel = new Categoria();
        $productos  = $productoModel->catalogo();
        $categorias = $categoriaModel->menu();

        ob_start();
        require dirname(__DIR__) . '/app/Views/home/index.php';
        $content = ob_get_clean();
        require dirname(__DIR__) . '/app/Views/layouts/public.php';
        exit;
}

