<?php

// Jmenný prostor pro feature testy v Laravelu
namespace Tests\Feature;

// Načtu trait RefreshDatabase pro čistou databázi při každém testu
use Illuminate\Foundation\Testing\RefreshDatabase;
// Načtu základní testovací třídu Laravelu
use Tests\TestCase;

// Feature test ověřující základní fungování aplikace
class ExampleTest extends TestCase
{
    // Po každém testu obnoví databázi do čistého stavu (spustí migrace)
    use RefreshDatabase;

    // Test ověřující, že hlavní stránka vrátí úspěšnou HTTP odpověď
    public function test_the_application_returns_a_successful_response(): void
    {
        // Pošlu simulovaný GET požadavek na kořenovou URL '/'
        $response = $this->get('/');

        // Ověřím, že odpověď má stavový kód 200 (OK)
        $response->assertStatus(200);
    }
}
