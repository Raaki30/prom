<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('order_id');
            $table->string('nis');
            $table->string('nama');
            $table->string('email');
            $table->string('phone');
            $table->string('kelas');
            $table->string('jumlah_tiket');
            $table->string('harga');
            $table->string('metodebayar');
            $table->string('bukti')->default('-');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('entry', ['yes', 'no'])->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
