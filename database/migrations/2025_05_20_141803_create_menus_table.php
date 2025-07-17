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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);        // Nama makanan/minuman
            $table->text('description')->nullable(); // Deskripsi makanan
            $table->decimal('price', 10, 2);    // Harga
            $table->string('image')->nullable(); // URL/path gambar
            $table->boolean('is_package')->default(false);
            $table->json('package_items')->nullable(); // daftar item ID dalam paket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
