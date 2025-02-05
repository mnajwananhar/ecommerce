<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seller_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Customer ID
            $table->string('nik'); // NIK
            $table->string('full_name'); // Nama lengkap // Alamat toko
            $table->string('selfie_photo'); // Path foto selfie
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status pengajuan
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_requests');
    }
};
