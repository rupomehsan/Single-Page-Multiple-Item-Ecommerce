<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/FAQManagement/FAQ/Database/create_f_a_qs_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('f_a_qs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->nullable();
            $table->string('question', 500)->nullable();
            $table->longtext('answer')->nullable();
            $table->string('category', 100)->nullable();
            $table->integer('display_order')->nullable();
            $table->enum('is_active', ['yes','no'])->nullable();
            $table->integer('view_count')->nullable();
            $table->integer('helpful_count')->nullable();

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
        Schema::dropIfExists('f_a_qs');
    }
};