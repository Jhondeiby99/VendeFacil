<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Si el usuario está logueado
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // Si el usuario no está logueado -> lo guardamos con session_id
            $table->string('session_id')->nullable()->index();

            // Relación con producto
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->integer('quantity')->default(1);

            // Precio al momento de añadir al carrito (por si cambia en el futuro)
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
