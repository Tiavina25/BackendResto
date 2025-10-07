<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plats', function (Blueprint $table) {
            $table->id();

            // Relation avec catégorie + suppression en cascade
            $table->foreignId('categorie_id')
                  ->constrained('categories')
                  ->onDelete('cascade');

            $table->string('nom', 150);
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->string('image_url')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('plats');
    }
};
