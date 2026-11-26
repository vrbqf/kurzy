<?php

// Jmenný prostor pro Eloquent modely v Laravelu
namespace App\Models;

// Načtu základní třídu Model pro práci s databázovou tabulkou
use Illuminate\Database\Eloquent\Model;

// Model reprezentující kurz (tabulka courses)
class Course extends Model
{
    // Sloupce povolené pro hromadné přiřazení (např. Course::create())
    protected $fillable = ['name', 'code', 'capacity', 'starts_at'];

    // Vztah 1:N – jeden kurz může mít více přihlášek
    public function enrollments()
    {
        // Vrátí vazbu na model Enrollment přes cizí klíč course_id
        return $this->hasMany(Enrollment::class);
    }

    // Pomocná metoda pro zjištění počtu aktivně obsazených míst v kurzu
    public function activeEnrollmentsCount(): int
    {
        // Vezmu všechny přihlášky tohoto kurzu
        return $this->enrollments()
            // Vyfiltruju jen ty, které mají stav 'enrolled' (cancelled a completed místo neblokují)
            ->where('status', 'enrolled')
            // Spočítám a vrátím výsledný počet jako celé číslo (int)
            ->count();
    }
}
