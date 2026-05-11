<?php

namespace Database\Seeders;

use App\Models\SecurityQuestion;
use Illuminate\Database\Seeder;

class SecurityQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            '¿Cuál es el nombre de tu primera mascota?',
            '¿En qué ciudad naciste?',
            '¿Cuál es tu color favorito?',
            '¿Cuál es el nombre de tu madre?',
            '¿Cuál era el nombre de tu primera escuela?',
            '¿Cuál es tu comida favorita?',
            '¿Cuál es el nombre de tu mejor amigo de la infancia?',
            '¿Cuál es el modelo de tu primer auto?',
            '¿En qué año te graduaste de la secundaria?',
            '¿Cuál es el nombre de tu autor favorito?',
            '¿Cuál es tu película favorita?',
            '¿Cuál es el nombre de tu abuelo materno?'
        ];

        foreach ($questions as $q) {
            SecurityQuestion::firstOrCreate(['question' => $q]);
        }
    }
}
