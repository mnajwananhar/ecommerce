<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User yang memberikan review
            $table->unsignedBigInteger('product_id'); // Produk yang direview
            $table->unsignedBigInteger('order_id'); // Order terkait
            $table->text('review'); // Isi review
            $table->decimal('rating', 2, 1); // Rating (contoh: 4.5)
            $table->string('image')->nullable(); // Gambar untuk review
            $table->timestamps();

            // Relasi
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
