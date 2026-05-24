<?php
namespace Modules\Management\ReviewManagement\Review\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\ReviewManagement\Review\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\ReviewManagement\Review\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'order_id' => $faker->randomNumber(8),
                'product_id' => $faker->randomNumber(8),
                'customer_id' => $faker->randomNumber(8),
                'customer_name' => $faker->text(255),
                'customer_location' => $faker->text(255),
                'rating' => $faker->randomNumber(5),
                'title' => $faker->text(255),
                'review_text' => $faker->paragraph,
                'verified_purchase' => $faker->boolean,
                'is_published' => $faker->boolean,
                'is_featured' => $faker->boolean,
                'helpful_count' => $faker->randomNumber(5),
                'unhelpful_count' => $faker->randomNumber(5),
                'admin_response' => $faker->paragraph,
                'admin_response_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}