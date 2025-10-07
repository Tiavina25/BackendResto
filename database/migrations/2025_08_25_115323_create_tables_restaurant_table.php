<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables_restaurant', function (Blueprint $table) {
            $table->id();                        // id auto-increment
            $table->string('numero', 20)->unique(); // numéro de la table
            $table->date('created_at')->useCurrent(); // date par défaut = aujourd'hui
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables_restaurant');
    }
};

