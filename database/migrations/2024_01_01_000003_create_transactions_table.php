<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('product_name');
            $table->string('product_slug');
            $table->string('product_image')->nullable();
            $table->integer('qty');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_price');
            $table->string('status');
            $table->string('remaining_time')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('ID')->on('user')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
