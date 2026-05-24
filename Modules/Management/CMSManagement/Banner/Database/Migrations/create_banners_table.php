<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CMSManagement/Banner/Database/create_banners_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->longtext('description')->nullable();
            $table->string('banner_image', 255)->nullable();
            $table->string('banner_image_mobile', 255)->nullable();
            $table->string('button_text', 100)->nullable();
            $table->string('button_link', 255)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->enum('is_active', ['yes','no'])->nullable();
            $table->integer('display_order')->nullable();
            $table->string('background_color', 50)->nullable();

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
        Schema::dropIfExists('banners');
    }
};