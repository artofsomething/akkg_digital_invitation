<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // ============ WEDDING TEMPLATES ============
        $wedding = Category::where('slug', 'wedding')->first();

        $weddingTemplates = [
            [
                'name'         => 'Wedding Elegant',
                'slug'         => 'wedding-elegant',
                'thumbnail'    => 'templates/thumbnails/wedding-elegant.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#2c1810',
                    'secondary' => '#f0e8df',
                    'accent'    => '#c9a96e',
                    'text'      => '#4a3728',
                ],
                'font_family'  => 'Playfair Display',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'Wedding Rustic',
                'slug'         => 'wedding-rustic',
                'thumbnail'    => 'templates/thumbnails/wedding-rustic.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#5c4033',
                    'secondary' => '#efebe9',
                    'accent'    => '#8d6e63',
                    'text'      => '#3e2723',
                ],
                'font_family'  => 'Lora',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'Wedding Modern',
                'slug'         => 'wedding-modern',
                'thumbnail'    => 'templates/thumbnails/wedding-modern.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#1a1a2e',
                    'secondary' => '#f5f5f5',
                    'accent'    => '#e94560',
                    'text'      => '#16213e',
                ],
                'font_family'  => 'Montserrat',
                'is_active'    => true,
                'is_premium'   => true,
            ],
            [
                'name'         => 'Wedding Floral',
                'slug'         => 'wedding-floral',
                'thumbnail'    => 'templates/thumbnails/wedding-floral.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#880e4f',
                    'secondary' => '#fce4ec',
                    'accent'    => '#f48fb1',
                    'text'      => '#4a148c',
                ],
                'font_family'  => 'Cormorant Garamond',
                'is_active'    => true,
                'is_premium'   => true,
            ],
        ];

        foreach ($weddingTemplates as $template) {
            Template::updateOrCreate(
                ['slug' => $template['slug']],
                array_merge($template, ['category_id' => $wedding->id])
            );
        }

        // ============ KIDS BIRTHDAY TEMPLATES ============
        $kidsBirthday = Category::where('slug', 'kids-birthday')->first();

        $kidsTemplates = [
            [
                'name'         => 'Kids Colorful',
                'slug'         => 'kids-colorful',
                'thumbnail'    => 'templates/thumbnails/kids-colorful.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#ff6f00',
                    'secondary' => '#fff8e1',
                    'accent'    => '#ffca28',
                    'text'      => '#e65100',
                ],
                'font_family'  => 'Nunito',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'Kids Cartoon',
                'slug'         => 'kids-cartoon',
                'thumbnail'    => 'templates/thumbnails/kids-cartoon.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#1565c0',
                    'secondary' => '#e3f2fd',
                    'accent'    => '#42a5f5',
                    'text'      => '#0d47a1',
                ],
                'font_family'  => 'Fredoka One',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'Kids Princess',
                'slug'         => 'kids-princess',
                'thumbnail'    => 'templates/thumbnails/kids-princess.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#ad1457',
                    'secondary' => '#fce4ec',
                    'accent'    => '#f06292',
                    'text'      => '#880e4f',
                ],
                'font_family'  => 'Dancing Script',
                'is_active'    => true,
                'is_premium'   => true,
            ],
        ];

        foreach ($kidsTemplates as $template) {
            Template::updateOrCreate(
                ['slug' => $template['slug']],
                array_merge($template, ['category_id' => $kidsBirthday->id])
            );
        }

        // ============ ADULT BIRTHDAY TEMPLATES ============
        $adultBirthday = Category::where('slug', 'adult-birthday')->first();

        $adultTemplates = [
            [
                'name'         => 'Adult Elegant',
                'slug'         => 'adult-elegant',
                'thumbnail'    => 'templates/thumbnails/adult-elegant.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#212121',
                    'secondary' => '#fafafa',
                    'accent'    => '#ffd700',
                    'text'      => '#424242',
                ],
                'font_family'  => 'Raleway',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'Adult Party',
                'slug'         => 'adult-party',
                'thumbnail'    => 'templates/thumbnails/adult-party.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#4a148c',
                    'secondary' => '#f3e5f5',
                    'accent'    => '#ab47bc',
                    'text'      => '#311b92',
                ],
                'font_family'  => 'Poppins',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'Adult Minimalist',
                'slug'         => 'adult-minimalist',
                'thumbnail'    => 'templates/thumbnails/adult-minimalist.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#37474f',
                    'secondary' => '#eceff1',
                    'accent'    => '#78909c',
                    'text'      => '#263238',
                ],
                'font_family'  => 'Inter',
                'is_active'    => true,
                'is_premium'   => true,
            ],
        ];

        foreach ($adultTemplates as $template) {
            Template::updateOrCreate(
                ['slug' => $template['slug']],
                array_merge($template, ['category_id' => $adultBirthday->id])
            );
        }

        // ============ GENERAL TEMPLATES ============
        $general = Category::where('slug', 'general')->first();

        $generalTemplates = [
            [
                'name'         => 'General Classic',
                'slug'         => 'general-classic',
                'thumbnail'    => 'templates/thumbnails/general-classic.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#1b5e20',
                    'secondary' => '#f1f8e9',
                    'accent'    => '#66bb6a',
                    'text'      => '#33691e',
                ],
                'font_family'  => 'Roboto',
                'is_active'    => true,
                'is_premium'   => false,
            ],
            [
                'name'         => 'General Modern',
                'slug'         => 'general-modern',
                'thumbnail'    => 'templates/thumbnails/general-modern.jpg',
                'preview_url'  => null,
                'color_scheme' => [
                    'primary'   => '#006064',
                    'secondary' => '#e0f7fa',
                    'accent'    => '#00acc1',
                    'text'      => '#004d40',
                ],
                'font_family'  => 'Open Sans',
                'is_active'    => true,
                'is_premium'   => false,
            ],
        ];

        foreach ($generalTemplates as $template) {
            Template::updateOrCreate(
                ['slug' => $template['slug']],
                array_merge($template, ['category_id' => $general->id])
            );
        }

        $this->command->info('Templates seeded successfully!');
    }
}