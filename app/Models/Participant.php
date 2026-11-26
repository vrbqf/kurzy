<?php

// Jmenný prostor pro Eloquent modely v Laravelu
namespace App\Models;

// Načtu základní třídu Model pro práci s databázovou tabulkou
use Illuminate\Database\Eloquent\Model;

// Model reprezentující účastníka kurzů (tabulka participants)
class Participant extends Model
{
    // Sloupce povolené pro hromadné přiřazení (např. Participant::create())
    protected $fillable = [
        // Jméno a příjmení účastníka
        'name',
        // Unikátní e-mailová adresa účastníka
        'email',
    ];
}
