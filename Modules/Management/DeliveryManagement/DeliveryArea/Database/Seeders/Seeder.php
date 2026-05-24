<?php
namespace Modules\Management\DeliveryManagement\DeliveryArea\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\DeliveryManagement\DeliveryArea\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\DeliveryManagement\DeliveryArea\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'area_name' => $faker->word,
                'division' => $faker->text(100),
                'district' => $faker->text(100),
                'thana' => $faker->text(100),
                'delivery_days' => $faker->randomNumber(5),
                'delivery_charge' => $faker->word,
                'is_available' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
                'cod_available' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
            ]);
        }
    }
}