<?php
namespace Modules\Management\CMSManagement\Testimonial\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\CMSManagement\Testimonial\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\CMSManagement\Testimonial\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'customer_name' => $faker->text(255),
                'customer_image' => $faker->text(255),
                'customer_location' => $faker->text(255),
                'testimonial_text' => $faker->paragraph,
                'rating' => $faker->randomNumber(5),
                'from_order_id' => $faker->randomNumber(8),
                'is_featured' => $faker->boolean,
                'is_published' => $faker->boolean,
                'display_order' => $faker->randomNumber(5),
            ]);
        }
    }
}