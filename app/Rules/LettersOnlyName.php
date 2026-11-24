<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LettersOnlyName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        // Solo letras Unicode (incluye tildes y Ñ/ñ), espacios y guiones simples
        if (preg_match('/^[\p{L}\s\'-]+$/u', trim((string) $value)) !== 1) {
            $fail('El campo :attribute solo puede contener letras y espacios (sin números ni símbolos).');
        }
    }
}
