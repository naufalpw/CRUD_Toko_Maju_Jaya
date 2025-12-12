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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Contoh: "Tambah Produk", "Transaksi Baru"
            $table->string('description'); // Penjelasan detail
            // Kita butuh ini agar kalau tipe-nya transaksi, kita bisa panggil tabel detailnya
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
