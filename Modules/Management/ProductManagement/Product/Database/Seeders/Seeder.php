<?php
namespace Modules\Management\ProductManagement\Product\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\ProductManagement\Product\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\ProductManagement\Product\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'name' => $faker->text(255),
                'description' => $faker->paragraph,
                'image' => $faker->word,
                'regular_price' => $faker->randomNumber(8),
                'sales_price' => $faker->randomNumber(8),
                'category_id' => $faker->randomNumber(5),
                'sku' => $faker->word,
                'is_featured' => $faker->boolean,
                'quantity' => $faker->randomNumber(8),
            ]);
        }
    }
}