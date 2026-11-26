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
        // Vytvořím novou tabulku s názvem 'courses'
        Schema::create('courses', function (Blueprint $table) {
            // Primární klíč 'id' (automaticky inkrementované celé číslo)
            $table->id();
            // Sloupec 'name' pro název kurzu (řetězec / VARCHAR)
            $table->string('name');
            // Sloupec 'code' pro kód kurzu s omezením UNIQUE (nesmí se opakovat)
            $table->string('code')->unique();
            // Sloupec 'capacity' pro kapacitu jako nezáporné celé číslo (kladná čísla a nula)
            $table->unsignedInteger('capacity');
            // Sloupec 'starts_at' pro datum a čas začátku kurzu
            $table->dateTime('starts_at');
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
        // Smažu tabulku 'courses', pokud existuje
        Schema::dropIfExists('courses');
    }
};
