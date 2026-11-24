<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SpanishDate implements ValidationRule
{
    public function __construct(
        private readonly ?int $minAge = null,
        private readonly ?int $maxAge = null,
        private readonly bool $mustBeBeforeToday = false,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        $parsed = self::parse((string) $value);
        if (!$parsed) {
            $fail('La fecha debe tener un formato válido en español (DD-MM-AAAA o DD/MM/AAAA).');
            return;
        }

        if ($this->mustBeBeforeToday && !$parsed->lt(Carbon::today())) {
            $fail('La fecha debe ser anterior al día de hoy.');
            return;
        }

        if ($this->minAge !== null && $parsed->age < $this->minAge) {
            $fail("La edad mínima permitida es de {$this->minAge} años.");
            return;
        }

        if ($this->maxAge !== null && $parsed->age >= $this->maxAge) {
            $fail("La edad debe ser menor a {$this->maxAge} años.");
        }
    }

    /**
     * Parsea fechas en formato español (d-m-Y o d/m/Y) y opcionalmente Y-m-d interno.
     */
    public static function parse(?string $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        $clean = trim($value);
        if ($clean === '') {
            return null;
        }

        foreach (['d-m-Y', 'd/m/Y', 'Y-m-d'] as $fmt) {
            try {
                $dt = Carbon::createFromFormat($fmt, $clean);
                if ($dt && $dt->format($fmt) === $clean) {
                    return $dt->startOfDay();
                }
            } catch (\Throwable) {
                // Intentar siguiente formato
            }
        }

        return null;
    }

    /**
     * Convierte una fecha en español (DD-MM-AAAA) a formato de base de datos (Y-m-d).
     */
    public static function toDatabase(?string $value): ?string
    {
        $dt = self::parse($value);
        return $dt ? $dt->format('Y-m-d') : null;
    }

    /**
     * Convierte una fecha de base de datos a formato español (DD-MM-AAAA).
     */
    public static function toSpanish(?string $value): string
    {
        $dt = self::parse($value);
        return $dt ? $dt->format('d-m-Y') : '';
    }
}
