<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'      => 'Wedding',
                'slug'      => 'wedding',
                'icon'      => '💍',
                'is_active' => true,
            ],
            [
                'name'      => 'Kids Birthday',
                'slug'      => 'kids-birthday',
                'icon'      => '🎈',
                'is_active' => true,
            ],
            [
                'name'      => 'Adult Birthday',
                'slug'      => 'adult-birthday',
                'icon'      => '🎂',
                'is_active' => true,
            ],
            [
                'name'      => 'General',
                'slug'      => 'general',
                'icon'      => '🎉',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],  // Check by slug
                $category                        // Data to insert/update
            );
        }

        $this->command->info('Categories seeded successfully!');
    }
}