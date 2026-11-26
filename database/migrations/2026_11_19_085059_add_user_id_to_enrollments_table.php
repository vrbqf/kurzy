<?php

// Načtu třídu Migration pro definování databázových změn
use Illuminate\Database\Migrations\Migration;
// Načtu třídu Blueprint pro popis úpravy sloupců a vazeb tabulky
use Illuminate\Database\Schema\Blueprint;
// Načtu fasádu Schema pro práci se strukturou tabulek v databázi
use Illuminate\Support\Facades\Schema;

// Vracím anonymní třídu migrace pro přidání cizího klíče user_id do tabulky enrollments
return new class extends Migration
{
    // Metoda up() se spustí při aplikování migrace (např. php artisan migrate)
    public function up(): void
    {
        // Upravím již existující tabulku 'enrollments'
        Schema::table('enrollments', function (Blueprint $table) {
            // Přidám sloupec user_id jako cizí klíč za sloupec participant_id; může být NULL, odkazuje na users.id a při smazání uživatele se nastaví na NULL
            $table->foreignId('user_id')->nullable()->after('participant_id')->constrained()->nullOnDelete();
        });
    }

    // Metoda down() vrátí změnu zpět (např. při php artisan migrate:rollback)
    public function down(): void
    {
        // Znovu otevřu tabulku 'enrollments' pro vrácení změny
        Schema::table('enrollments', function (Blueprint $table) {
            // Smažu cizí klíč i samotný sloupec user_id
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
