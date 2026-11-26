<?php

// Načtu třídu Migration pro definování databázových změn
use Illuminate\Database\Migrations\Migration;
// Načtu třídu Blueprint pro popis struktury sloupců a cizích klíčů tabulky
use Illuminate\Database\Schema\Blueprint;
// Načtu fasádu Schema pro vytváření a mazání tabulek v databázi
use Illuminate\Support\Facades\Schema;

// Vracím anonymní třídu migrace pro vytvoření tabulky přihlášek enrollments
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Metoda up() vytvoří tabulku při spuštění migrací (php artisan migrate)
    public function up(): void
    {
        // Vytvořím novou tabulku 'enrollments'
        Schema::create('enrollments', function (Blueprint $table) {
            // Primární klíč 'id' (automaticky inkrementované celé číslo)
            $table->id();
            // Cizí klíč na tabulku courses; při smazání kurzu se smažou i jeho přihlášky (cascadeOnDelete)
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            // Cizí klíč na tabulku participants; při smazání účastníka se smažou i jeho přihlášky
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            // Datum a čas přihlášení, jako výchozí hodnota se použije aktuální čas (useCurrent)
            $table->timestamp('enrolled_at')->useCurrent();
            // Stav přihlášky omezený na povolené hodnoty se základním stavem 'enrolled'
            $table->enum('status', ['enrolled', 'cancelled', 'completed'])->default('enrolled');
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
        // Smažu tabulku 'enrollments', pokud existuje
        Schema::dropIfExists('enrollments');
    }
};
