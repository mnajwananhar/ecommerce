<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User yang memesan
            $table->string('shipping_address'); // Alamat pengiriman
            $table->string('courier'); // Kurir yang dipilih
            $table->decimal('shipping_cost', 10, 2); // Ongkos kirim
            $table->decimal('total_price', 10, 2); // Total harga (produk + ongkir)
            $table->string('status')->default('pending'); // Status pesanan
            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
