<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CustomerManagement/Customer/Database/create_customers_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->longtext('address')->nullable();
            $table->string('district', 100)->nullable();
            $table->string('thana', 100)->nullable();
            $table->string('village', 100)->nullable();
            $table->integer('total_orders')->nullable();
            $table->string('total_spent', 100)->nullable();
            $table->string('lifetime_discount', 100)->nullable();
            $table->integer('loyalty_points')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->date('last_order_date')->nullable();

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
        Schema::dropIfExists('customers');
    }
};