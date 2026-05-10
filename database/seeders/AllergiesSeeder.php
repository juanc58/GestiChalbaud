<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergiesSeeder extends Seeder
{
    public function run(): void
    {
        $allergies = [
            // Medicamentos
            ['name' => 'Penicilina',        'category' => 'Medicamento'],
            ['name' => 'Amoxicilina',       'category' => 'Medicamento'],
            ['name' => 'Aspirina (AAS)',     'category' => 'Medicamento'],
            ['name' => 'Ibuprofeno',         'category' => 'Medicamento'],
            ['name' => 'Sulfonamidas',       'category' => 'Medicamento'],
            ['name' => 'Cefalosporinas',     'category' => 'Medicamento'],
            // Alimentos
            ['name' => 'Maní / Cacahuate',  'category' => 'Alimento'],
            ['name' => 'Mariscos',           'category' => 'Alimento'],
            ['name' => 'Pescado',            'category' => 'Alimento'],
            ['name' => 'Leche de Vaca',     'category' => 'Alimento'],
            ['name' => 'Huevo',              'category' => 'Alimento'],
            ['name' => 'Trigo / Gluten',    'category' => 'Alimento'],
            ['name' => 'Soya',               'category' => 'Alimento'],
            ['name' => 'Nueces y Frutos Secos', 'category' => 'Alimento'],
            ['name' => 'Frutas Cítricas',   'category' => 'Alimento'],
            ['name' => 'Chocolate',          'category' => 'Alimento'],
            // Ambiental / Contacto
            ['name' => 'Polen',              'category' => 'Ambiental'],
            ['name' => 'Ácaros del Polvo',  'category' => 'Ambiental'],
            ['name' => 'Pelo de Animales',  'category' => 'Ambiental'],
            ['name' => 'Látex',              'category' => 'Contacto'],
            ['name' => 'Picaduras de Abeja','category' => 'Insecto'],
            ['name' => 'Picaduras de Avispa','category' => 'Insecto'],
            ['name' => 'Níquel (metales)',  'category' => 'Contacto'],
            ['name' => 'Moho / Hongos',     'category' => 'Ambiental'],
        ];

        DB::table('allergies')->insertOrIgnore($allergies);
    }
}
