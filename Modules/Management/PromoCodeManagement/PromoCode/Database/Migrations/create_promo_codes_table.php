<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/PromoCodeManagement/PromoCode/Database/create_promo_codes_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->enum('discount_type', ['percentage','fixed'])->nullable();
            $table->string('discount_value', 100)->nullable();
            $table->string('minimum_order_amount', 100)->nullable();
            $table->string('maximum_discount_amount', 100)->nullable();
            $table->longtext('applicable_categories')->nullable();
            $table->integer('max_usage')->nullable();
            $table->integer('current_usage')->nullable();
            $table->integer('max_usage_per_customer')->nullable();
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_until')->nullable();
            $table->enum('is_active', ['yes','no'])->nullable();

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
        Schema::dropIfExists('promo_codes');
    }
};