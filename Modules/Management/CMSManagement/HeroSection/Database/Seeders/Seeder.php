<?php
namespace Modules\Management\CMSManagement\HeroSection\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\CMSManagement\HeroSection\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\CMSManagement\HeroSection\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'heading' => $faker->text(255),
                'subheading' => $faker->text(255),
                'description' => $faker->paragraph,
                'button_text' => $faker->text(100),
                'button_link' => $faker->text(255),
                'background_image' => $faker->text(255),
                'image_alt_text' => $faker->text(255),
                'text_color' => $faker->text(50),
                'background_color' => $faker->text(50),
                'is_active' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
                'display_order' => $faker->randomNumber(5),
            ]);
        }
    }
}