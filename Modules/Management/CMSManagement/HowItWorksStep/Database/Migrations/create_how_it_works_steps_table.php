<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CMSManagement/HowItWorksStep/Database/create_how_it_works_steps_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('how_it_works_steps', function (Blueprint $table) {
            $table->id();
            $table->integer('step_number')->nullable();
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
        Schema::dropIfExists('how_it_works_steps');
    }
};