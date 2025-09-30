<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['en' => 'tech', 'ar' => 'تقنية', 'color' => '#FF0000'],
            ['en' => 'social media', 'ar' => 'وسائل التواصل الاجتماعي', 'color' => '#00FF00'],
            ['en' => 'growth', 'ar' => 'نمو', 'color' => '#0000FF'],
            ['en' => 'management', 'ar' => 'إدارة', 'color' => '#ff8633'],
            ['en' => 'call center', 'ar' => 'مركز الاتصال', 'color' => '#33ff52'],
            ['en' => 'front end', 'ar' => 'الواجهة الأمامية', 'color' => '#3383ff'],
            ['en' => 'back end', 'ar' => 'الواجهة الخلفية', 'color' => '#ff33f9'],
        ];

        // Insert the categories with translations into the database
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => json_encode([
                    'en' => $category['en'],
                    'ar' => $category['ar'],
                ]),
                'color' => $category['color'],
            ]);
        }
    }
}
