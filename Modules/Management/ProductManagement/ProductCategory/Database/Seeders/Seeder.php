<?php
namespace Modules\Management\ProductManagement\ProductCategory\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\ProductManagement\ProductCategory\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\ProductManagement\ProductCategory\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'category_name' => $faker->text(255),
                'description' => $faker->paragraph,
                'category_image' => $faker->text(255),
                'display_order' => $faker->randomNumber(5),
                'parent_category_id' => $faker->randomNumber(5),
            ]);
        }
    }
}