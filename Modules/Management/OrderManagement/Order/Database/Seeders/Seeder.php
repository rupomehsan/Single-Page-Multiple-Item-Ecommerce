<?php
namespace Modules\Management\OrderManagement\Order\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\OrderManagement\Order\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\OrderManagement\Order\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'order_number' => $faker->word,
                'customer_name' => $faker->text(255),
                'customer_phone' => $faker->text(20),
                'customer_email' => $faker->text(255),
                'delivery_address' => $faker->paragraph,
                'delivery_area_id' => $faker->randomNumber(5),
                'delivery_district' => $faker->text(100),
                'delivery_thana' => $faker->text(100),
                'delivery_village' => $faker->text(100),
                'delivery_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'delivered_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
                'subtotal' => $faker->word,
                'shipping_charge' => $faker->word,
                'discount_amount' => $faker->word,
                'promo_code_used' => $faker->text(100),
                'total_amount' => $faker->word,
                'payment_method' => $faker->text(50),
                'payment_status' => $faker->randomElement(array (
  0 => 'pending',
  1 => 'completed',
  2 => 'failed',
  3 => 'refunded',
)),
                'order_status' => $faker->randomElement(array (
  0 => 'pending',
  1 => 'confirmed',
  2 => 'processing',
  3 => 'shipped',
  4 => 'delivered',
  5 => 'cancelled',
  6 => 'returned',
)),
                'special_notes' => $faker->paragraph,
                'admin_notes' => $faker->paragraph,
                'ip_address' => $faker->text(45),
                'source' => $faker->randomElement(array (
  0 => 'website',
  1 => 'mobile',
  2 => 'whatsapp',
  3 => 'phone',
)),
            ]);
        }
    }
}