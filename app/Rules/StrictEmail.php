<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrictEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        $email = mb_strtolower(trim((string) $value), 'UTF-8');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/', $email) !== 1) {
            $fail('El correo electrónico debe tener un formato válido con dominio completo (ej. nombre@correo.com).');
        }
    }
}
