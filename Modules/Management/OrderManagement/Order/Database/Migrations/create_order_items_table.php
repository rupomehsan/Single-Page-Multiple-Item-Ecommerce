<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/Modules/Management/OrderManagement/Order/Database/Migrations/create_order_items_table.php' 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit_price', 100)->nullable();
            $table->string('total_price', 100)->nullable();
            $table->string('discount_per_item', 100)->nullable();

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
        Schema::dropIfExists('order_items');
    }
};