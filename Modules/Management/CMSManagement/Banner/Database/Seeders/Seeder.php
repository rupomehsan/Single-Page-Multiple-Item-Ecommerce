<?php
namespace Modules\Management\CMSManagement\Banner\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\CMSManagement\Banner\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\CMSManagement\Banner\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'title' => $faker->text(255),
                'description' => $faker->paragraph,
                'banner_image' => $faker->text(255),
                'banner_image_mobile' => $faker->text(255),
                'button_text' => $faker->text(100),
                'button_link' => $faker->text(255),
                'alt_text' => $faker->text(255),
                'start_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'end_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'is_active' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
                'display_order' => $faker->randomNumber(5),
                'background_color' => $faker->text(50),
            ]);
        }
    }
}