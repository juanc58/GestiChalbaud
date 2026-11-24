<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VenezuelanPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        $normalized = self::normalize((string) $value);

        // Formato venezolano (0412/0414/0424/0416/0426/02XX + 7 dígitos = 11 dígitos)
        // o formato internacional (+código país y 8-15 dígitos)
        $isVzla = preg_match('/^0(412|414|424|416|426|2\d{2})\d{7}$/', $normalized) === 1;
        $isIntl = preg_match('/^\+[1-9]\d{7,14}$/', $normalized) === 1;

        if (!$isVzla && !$isIntl) {
            $fail('El teléfono debe tener un formato válido (ej. 04121234567, 02121234567 o +584121234567).');
        }
    }

    public static function normalize(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $trimmed = trim($value);
        $hasPlus = str_starts_with($trimmed, '+');
        $digits  = preg_replace('/\D/', '', $trimmed);

        return $hasPlus ? ('+' . $digits) : $digits;
    }
}
