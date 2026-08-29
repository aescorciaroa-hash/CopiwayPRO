<?php
/** Traduce una clave de periodo ("hoy", "semana", ...) a un rango de fechas. */
class Periodo
{
    public const OPCIONES = [
        'hoy'           => 'Hoy',
        'semana'        => 'Esta semana',
        'semana_pasada' => 'Semana pasada',
        'mes'           => 'Este mes',
        'mes_pasado'    => 'Mes pasado',
    ];

    /** Devuelve [desde, hasta] en formato 'Y-m-d H:i:s'. */
    public static function rango(string $clave): array
    {
        // DateTimeImmutable: cada llamada a modify() devuelve una fecha nueva
        // y no toca la original, asi podemos calcular inicio y fin por separado.
        $hoy = new \DateTimeImmutable('today');

        // Valor por defecto: el periodo "hoy" (desde hoy hasta hoy).
        $desde = $hoy;
        $hasta = $hoy;

        if ($clave === 'semana') {
            $desde = $hoy->modify('monday this week');
            $hasta = $hoy->modify('sunday this week');
        } elseif ($clave === 'semana_pasada') {
            $desde = $hoy->modify('monday last week');
            $hasta = $hoy->modify('sunday last week');
        } elseif ($clave === 'mes') {
            $desde = $hoy->modify('first day of this month');
            $hasta = $hoy->modify('last day of this month');
        } elseif ($clave === 'mes_pasado') {
            $desde = $hoy->modify('first day of last month');
            $hasta = $hoy->modify('last day of last month');
        }

        // Se devuelve el dia completo: desde las 00:00:00 hasta las 23:59:59.
        return [
            $desde->format('Y-m-d') . ' 00:00:00',
            $hasta->format('Y-m-d') . ' 23:59:59',
        ];
    }

    public static function valida(?string $clave): string
    {
        return array_key_exists($clave, self::OPCIONES) ? $clave : 'semana';
    }
}
