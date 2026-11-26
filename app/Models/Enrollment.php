<?php

// Jmenný prostor pro Eloquent modely v Laravelu
namespace App\Models;

// Načtu základní třídu Model pro práci s databázovou tabulkou
use Illuminate\Database\Eloquent\Model;

// Model reprezentující přihlášku účastníka na kurz (tabulka enrollments)
class Enrollment extends Model
{
    // Seznam sloupců, které lze hromadně vyplnit například přes Enrollment::create()
    protected $fillable = [
        // Cizí klíč odkazující na konkrétní kurz
        'course_id',
        // Cizí klíč odkazující na konkrétního účastníka
        'participant_id',
        // Datum a čas, kdy byl účastník přihlášen
        'enrolled_at',
        // Aktuální stav přihlášky (enrolled, cancelled, completed)
        'status',
    ];

    // Vztah N:1 – každá přihláška patří jednomu kurzu
    public function course()
    {
        // Vrátí vazbu na model Course podle course_id
        return $this->belongsTo(Course::class);
    }

    // Vztah N:1 – každá přihláška patří jednomu účastníkovi
    public function participant()
    {
        // Vrátí vazbu na model Participant podle participant_id
        return $this->belongsTo(Participant::class);
    }
}
