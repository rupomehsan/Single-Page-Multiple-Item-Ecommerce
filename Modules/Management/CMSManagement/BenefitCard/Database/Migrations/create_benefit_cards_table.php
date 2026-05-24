<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CMSManagement/BenefitCard/Database/create_benefit_cards_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('benefit_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->longtext('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('icon_image', 255)->nullable();
            $table->integer('display_order')->nullable();
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
        Schema::dropIfExists('benefit_cards');
    }
};