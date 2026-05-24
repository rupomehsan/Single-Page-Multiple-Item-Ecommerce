<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CMSManagement/Testimonial/Database/create_testimonials_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 255)->nullable();
            $table->string('customer_image', 255)->nullable();
            $table->string('customer_location', 255)->nullable();
            $table->longtext('testimonial_text')->nullable();
            $table->integer('rating')->nullable();
            $table->bigInteger('from_order_id')->nullable();
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_published')->default(0);
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
        Schema::dropIfExists('testimonials');
    }
};