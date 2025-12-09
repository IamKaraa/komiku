<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Aksi', 'slug' => 'action'],
            ['name' => 'Romantis', 'slug' => 'romance'],
            ['name' => 'Komedi', 'slug' => 'comedy'],
            ['name' => 'Fantasi', 'slug' => 'fantasy'],
            ['name' => 'Horor', 'slug' => 'horror'],
            ['name' => 'Petualangan', 'slug' => 'adventure'],
            ['name' => 'Misteri', 'slug' => 'mystery'],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
