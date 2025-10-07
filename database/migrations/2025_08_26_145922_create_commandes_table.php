<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained('tables_restaurant');
            $table->enum('type_commande', ['SUR_PLACE','A_EMPORTER']);
            $table->enum('statut', ['EN_ATTENTE','PREPARATION','PRET','TERMINE'])->default('EN_ATTENTE');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
