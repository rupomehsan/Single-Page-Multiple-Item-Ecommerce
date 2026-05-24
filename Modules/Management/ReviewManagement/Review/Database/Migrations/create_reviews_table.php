<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/ReviewManagement/Review/Database/create_reviews_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('customer_name', 255)->nullable();
            $table->string('customer_location', 255)->nullable();
            $table->integer('rating')->nullable();
            $table->string('title', 255)->nullable();
            $table->longtext('review_text')->nullable();
            $table->tinyInteger('verified_purchase')->default(0);
            $table->tinyInteger('is_published')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->integer('helpful_count')->nullable();
            $table->integer('unhelpful_count')->nullable();
            $table->longtext('admin_response')->nullable();
            $table->datetime('admin_response_date')->nullable();

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
        Schema::dropIfExists('reviews');
    }
};