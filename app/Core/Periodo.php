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
        $hoy = new \DateTimeImmutable('today');

        [$desde, $hasta] = match ($clave) {
            'semana' => [
                $hoy->modify('monday this week'),
                $hoy->modify('sunday this week'),
            ],
            'semana_pasada' => [
                $hoy->modify('monday last week'),
                $hoy->modify('sunday last week'),
            ],
            'mes' => [
                $hoy->modify('first day of this month'),
                $hoy->modify('last day of this month'),
            ],
            'mes_pasado' => [
                $hoy->modify('first day of last month'),
                $hoy->modify('last day of last month'),
            ],
            default => [$hoy, $hoy], // hoy
        };

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
