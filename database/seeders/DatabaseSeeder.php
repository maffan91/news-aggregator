<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders for other models
        $this->call([
            AuthorSeeder::class,
            CategorySeeder::class,
            SourceSeeder::class,
            ArticleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
