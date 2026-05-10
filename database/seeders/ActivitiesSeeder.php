<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            // Deportes
            ['name' => 'Fútbol',              'category' => 'Deporte'],
            ['name' => 'Béisbol',             'category' => 'Deporte'],
            ['name' => 'Baloncesto',          'category' => 'Deporte'],
            ['name' => 'Voleibol',            'category' => 'Deporte'],
            ['name' => 'Natación',            'category' => 'Deporte'],
            ['name' => 'Atletismo',           'category' => 'Deporte'],
            ['name' => 'Tenis de Mesa',       'category' => 'Deporte'],
            ['name' => 'Ajedrez',             'category' => 'Deporte'],
            ['name' => 'Ciclismo',            'category' => 'Deporte'],
            ['name' => 'Artes Marciales',     'category' => 'Deporte'],
            ['name' => 'Gimnasia',            'category' => 'Deporte'],
            ['name' => 'Softbol',             'category' => 'Deporte'],
            // Música
            ['name' => 'Canto',               'category' => 'Música'],
            ['name' => 'Guitarra',            'category' => 'Música'],
            ['name' => 'Piano',               'category' => 'Música'],
            ['name' => 'Batería / Percusión', 'category' => 'Música'],
            ['name' => 'Flauta',              'category' => 'Música'],
            ['name' => 'Cuatro (Llanero)',    'category' => 'Música'],
            // Arte y Cultura
            ['name' => 'Danza Clásica',       'category' => 'Arte'],
            ['name' => 'Danza Folclórica',    'category' => 'Arte'],
            ['name' => 'Teatro',              'category' => 'Arte'],
            ['name' => 'Dibujo y Pintura',   'category' => 'Arte'],
            ['name' => 'Escultura',           'category' => 'Arte'],
            ['name' => 'Fotografía',          'category' => 'Arte'],
            // Recreación
            ['name' => 'Lectura',             'category' => 'Recreación'],
            ['name' => 'Videojuegos',         'category' => 'Recreación'],
            ['name' => 'Cocina',              'category' => 'Recreación'],
            ['name' => 'Jardinería',          'category' => 'Recreación'],
            ['name' => 'Programación',        'category' => 'Tecnología'],
            ['name' => 'Robótica',            'category' => 'Tecnología'],
        ];

        DB::table('activities')->insertOrIgnore($activities);
    }
}
