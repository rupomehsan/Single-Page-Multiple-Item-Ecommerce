<?php
namespace Modules\Management\PromoCodeManagement\PromoCode\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\PromoCodeManagement\PromoCode\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\PromoCodeManagement\PromoCode\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'code' => $faker->word,
                'description' => $faker->text(255),
                'discount_type' => $faker->randomElement(array (
  0 => 'percentage',
  1 => 'fixed',
)),
                'discount_value' => $faker->word,
                'minimum_order_amount' => $faker->word,
                'maximum_discount_amount' => $faker->word,
                'applicable_categories' => $faker->paragraph,
                'max_usage' => $faker->randomNumber(5),
                'current_usage' => $faker->randomNumber(5),
                'max_usage_per_customer' => $faker->randomNumber(5),
                'valid_from' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'valid_until' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'is_active' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
            ]);
        }
    }
}