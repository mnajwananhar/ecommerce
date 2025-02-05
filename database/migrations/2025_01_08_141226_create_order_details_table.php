<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Foreign key untuk tabel orders
            $table->unsignedBigInteger('product_id'); // Foreign key untuk tabel products
            $table->integer('quantity'); // Jumlah produk yang dipesan
            $table->decimal('price', 10, 2); // Harga per produk
            $table->timestamps();

            // Relasi ke tabel orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Relasi ke tabel products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
