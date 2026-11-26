<?php

// Načtu třídu Migration pro definování databázových změn
use Illuminate\Database\Migrations\Migration;
// Načtu třídu Blueprint pro popis struktury sloupců tabulky
use Illuminate\Database\Schema\Blueprint;
// Načtu fasádu Schema pro vytváření a mazání tabulek v databázi
use Illuminate\Support\Facades\Schema;

// Vracím anonymní třídu migrace, kterou Laravel spustí při migraci
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Metoda up() se spustí při vytváření tabulky (např. php artisan migrate)
    public function up(): void
    {
        // Vytvořím novou tabulku s názvem 'participants'
        Schema::create('participants', function (Blueprint $table) {
            // Primární klíč 'id' (automaticky inkrementované celé číslo)
            $table->id();
            // Sloupec 'name' pro jméno účastníka (řetězec / VARCHAR)
            $table->string('name');
            // Sloupec 'email' pro e-mail účastníka s omezením UNIQUE (nesmí se opakovat)
            $table->string('email')->unique();
            // Vytvoří sloupce 'created_at' a 'updated_at' pro automatické časové značky
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    // Metoda down() vrátí změnu zpět (např. při php artisan migrate:rollback)
    public function down(): void
    {
        // Smažu tabulku 'participants', pokud existuje
        Schema::dropIfExists('participants');
    }
};
