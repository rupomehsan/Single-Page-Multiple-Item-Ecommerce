<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/DeliveryManagement/DeliveryArea/Database/create_delivery_areas_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_areas', function (Blueprint $table) {
            $table->id();
            $table->string('area_name', 100)->nullable();
            $table->string('division', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('thana', 100)->nullable();
            $table->integer('delivery_days')->nullable();
            $table->string('delivery_charge', 100)->nullable();
            $table->enum('is_available', ['yes','no'])->nullable();
            $table->enum('cod_available', ['yes','no'])->nullable();

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
        Schema::dropIfExists('delivery_areas');
    }
};