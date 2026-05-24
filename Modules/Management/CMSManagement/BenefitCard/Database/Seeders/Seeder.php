<?php
namespace Modules\Management\CMSManagement\BenefitCard\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\CMSManagement\BenefitCard\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\CMSManagement\BenefitCard\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'title' => $faker->text(255),
                'description' => $faker->paragraph,
                'icon' => $faker->text(100),
                'icon_image' => $faker->text(255),
                'display_order' => $faker->randomNumber(5),
                'is_active' => $faker->randomElement(array (
  0 => 'yes',
  1 => 'no',
)),
            ]);
        }
    }
}