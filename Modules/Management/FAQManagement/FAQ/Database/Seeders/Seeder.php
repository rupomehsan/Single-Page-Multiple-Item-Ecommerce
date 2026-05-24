<?php
namespace Modules\Management\FAQManagement\FAQ\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\FAQManagement\FAQ\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\FAQManagement\FAQ\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'product_id' => $faker->randomNumber(8),
                'question' => $faker->text(500),
                'answer' => $faker->paragraph,
                'category' => $faker->text(100),
                'display_order' => $faker->randomNumber(5),
                'is_active' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
                'view_count' => $faker->randomNumber(5),
                'helpful_count' => $faker->randomNumber(5),
            ]);
        }
    }
}