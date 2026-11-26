<?php

// Jmenný prostor pro feature testy v Laravelu
namespace Tests\Feature;

// Načtu model Course (pokud bych ho chtěla v testu přímo použít)
use App\Models\Course;
// Načtu trait RefreshDatabase pro čistou databázi při spuštění testu
use Illuminate\Foundation\Testing\RefreshDatabase;
// Načtu základní testovací třídu Laravelu
use Tests\TestCase;

// Feature test ověřující vytvoření a uložení kurzu přes HTTP požadavek
class CourseVerificationTest extends TestCase
{
    // Zajistí čistou databázi před/po testu spuštěním migrací
    use RefreshDatabase;

    // Test ověřující automatické vytvoření kurzu a jeho uložení v databázi
    public function test_automatic_course_creation_and_verification(): void
    {
        // Odešlu POST požadavek na /courses s testovacími daty nového kurzu
        $response = $this->post('/courses', [
            // Testovací název kurzu
            'name' => 'Ověřovací kurz',
            // Unikátní testovací kód kurzu
            'code' => 'AUTO-CHECK-OK',
            // Testovací kapacita kurzu
            'capacity' => 10,
            // Datum a čas začátku kurzu nastavené za 7 dní od teď
            'starts_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        // Ověřím, že v tabulce courses skutečně existuje záznam se zadanými hodnotami
        $this->assertDatabaseHas('courses', [
            // Kontrolovaný kód kurzu
            'code' => 'AUTO-CHECK-OK',
            // Kontrolovaný název kurzu
            'name' => 'Ověřovací kurz',
            // Kontrolovaná kapacita kurzu
            'capacity' => 10,
        ]);
    }
}
