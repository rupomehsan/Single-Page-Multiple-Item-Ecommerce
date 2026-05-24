<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/OrderManagement/Order/Database/create_orders_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->nullable();
            $table->string('customer_name', 255)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_email', 255)->nullable();
            $table->longtext('delivery_address')->nullable();
            $table->integer('delivery_area_id')->nullable();
            $table->string('delivery_district', 100)->nullable();
            $table->string('delivery_thana', 100)->nullable();
            $table->string('delivery_village', 100)->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->string('subtotal', 100)->nullable();
            $table->string('shipping_charge', 100)->nullable();
            $table->string('discount_amount', 100)->nullable();
            $table->string('promo_code_used', 100)->nullable();
            $table->string('total_amount', 100)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->enum('payment_status', ['pending','completed','failed','refunded'])->nullable();
            $table->enum('order_status', ['pending','confirmed','processing','shipped','delivered','cancelled','returned'])->nullable();
            $table->longtext('special_notes')->nullable();
            $table->longtext('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->enum('source', ['website','mobile','whatsapp','phone'])->nullable();

            $table->bigInteger('creator')->unsigned()->nullable();
            $table->string('slug', 50)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};