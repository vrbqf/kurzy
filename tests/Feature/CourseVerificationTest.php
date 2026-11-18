<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_automatic_course_creation_and_verification(): void
    {
        // 1. Vytvoření kurzu přes požadavek
        $response = $this->post('/courses', [
            'name' => 'Ověřovací kurz',
            'code' => 'AUTO-CHECK-OK',
            'capacity' => 10,
            'starts_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        // 2. Ověření, že kurz je úspěšně uložen v databázi
        $this->assertDatabaseHas('courses', [
            'code' => 'AUTO-CHECK-OK',
            'name' => 'Ověřovací kurz',
            'capacity' => 10,
        ]);
    }
}
