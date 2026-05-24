<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CMSManagement/HeroSection/Database/create_hero_sections_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading', 255)->nullable();
            $table->string('subheading', 255)->nullable();
            $table->longtext('description')->nullable();
            $table->string('button_text', 100)->nullable();
            $table->string('button_link', 255)->nullable();
            $table->string('background_image', 255)->nullable();
            $table->string('image_alt_text', 255)->nullable();
            $table->string('text_color', 50)->nullable();
            $table->string('background_color', 50)->nullable();
            $table->enum('is_active', ['yes','no'])->nullable();
            $table->integer('display_order')->nullable();

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
        Schema::dropIfExists('hero_sections');
    }
};