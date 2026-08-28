<?php
/**
 * Modelo Carrito (manejo en $_SESSION).
 */
class Carrito
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function items(): array
    {
        return $_SESSION['carrito'] ?? [];
    }

    public function cantidad(): int
    {
        return array_sum(array_map(fn($i) => $i['cantidad'], $this->items()));
    }

    public function subtotalItem(array $item): float
    {
        $extras = 0.0;
        foreach ($item['personalizaciones'] as $p) {
            if ($p['accion'] === 'agregar') $extras += (float) $p['costo'];
        }
        return ((float) $item['precio_base'] + $extras) * (int) $item['cantidad'];
    }

    public function subtotal(): float
    {
        return array_sum(array_map([$this, 'subtotalItem'], $this->items()));
    }

    public function agregar(string $idProducto, int $cantidad, array $personalizaciones): void
    {
        $productoModel = new Producto();
        $prod = $productoModel->find($idProducto);
        if (!$prod) return;

        $items = $this->items();
        $key = substr(md5($idProducto . json_encode($personalizaciones)), 0, 12);

        if (isset($items[$key])) {
            $items[$key]['cantidad'] += $cantidad;
        } else {
            $items[$key] = [
                'key'               => $key,
                'id_producto'       => $idProducto,
                'nombre'            => $prod['nombre'],
                'precio_base'       => (float) $prod['precio_venta'],
                'cantidad'          => $cantidad,
                'personalizaciones' => $personalizaciones,
            ];
        }
        $_SESSION['carrito'] = $items;
    }

    public function actualizar(string $key, int $cantidad, ?array $personalizaciones = null): void
    {
        $items = $this->items();
        if (!isset($items[$key])) return;
        if ($cantidad <= 0) {
            unset($items[$key]);
        } else {
            $items[$key]['cantidad'] = $cantidad;
            if ($personalizaciones !== null) {
                $items[$key]['personalizaciones'] = $personalizaciones;
            }
        }
        $_SESSION['carrito'] = $items;
    }

    public function quitar(string $key): void
    {
        $items = $this->items();
        unset($items[$key]);
        $_SESSION['carrito'] = $items;
    }

    public function vaciar(): void
    {
        unset($_SESSION['carrito']);
    }

    public function aLineas(): array
    {
        $lineas = [];
        foreach ($this->items() as $item) {
            $pers = [];
            foreach ($item['personalizaciones'] as $p) {
                $pers[] = [
                    'id_ingrediente' => $p['id_ingrediente'],
                    'accion'         => $p['accion'] === 'agregar' ? 'agregar' : 'quitar',
                    'costo'          => (float) ($p['costo'] ?? 0),
                ];
            }
            $lineas[] = [
                'id_producto'       => $item['id_producto'],
                'cantidad'          => $item['cantidad'],
                'personalizaciones' => $pers,
            ];
        }
        return $lineas;
    }
}

