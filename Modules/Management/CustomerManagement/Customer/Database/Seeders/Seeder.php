<?php
namespace Modules\Management\CustomerManagement\Customer\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\CustomerManagement\Customer\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\CustomerManagement\Customer\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'user_id' => $faker->randomNumber(8),
                'phone_number' => $faker->text(20),
                'address' => $faker->paragraph,
                'district' => $faker->text(100),
                'thana' => $faker->text(100),
                'village' => $faker->text(100),
                'total_orders' => $faker->randomNumber(5),
                'total_spent' => $faker->word,
                'lifetime_discount' => $faker->word,
                'loyalty_points' => $faker->randomNumber(5),
                'is_verified' => $faker->boolean,
                'last_order_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}