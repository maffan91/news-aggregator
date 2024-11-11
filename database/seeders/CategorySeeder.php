<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fixed categories for simplification
        $categories = ['Business', 'Entertainment', 'Health', 'Technology', 'Sports'];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category]
            );
        }
    }
}
