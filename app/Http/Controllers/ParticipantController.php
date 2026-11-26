<?php
// Jmenný prostor pro kontrolery v Laravelu
namespace App\Http\Controllers;

// Načtu model Participant pro práci s účastníky v databázi
use App\Models\Participant;
// Načtu třídu Request pro práci s daty z odeslaného formuláře
use Illuminate\Http\Request;

// Kontroler pro správu účastníků kurzů
class ParticipantController extends Controller
{
    // Metoda pro vytvoření a uložení nového účastníka
    public function store(Request $request)
    {
        // Ověřím správnost údajů z formuláře
        $validated = $request->validate([
            // Jméno je povinné, musí být text a mít maximálně 255 znaků
            'name' => 'required|string|max:255',
            // E-mail je povinný, musí mít platný formát a nesmí už v tabulce participants existovat
            'email' => 'required|email|unique:participants,email',
        ]);

        // Vytvořím a uložím nového účastníka do databáze podle zvalidovaných dat
        Participant::create($validated);

        // Vrátím se zpět na předchozí stránku s hláškou o úspěšném přidání
        return redirect()->back()->with('success', 'Účastník byl přidán.');
    }

    // Metoda pro smazání konkrétního účastníka (Laravel ho automaticky najde podle ID v URL)
    public function destroy(Participant $participant)
    {
        // Smažu záznam účastníka z databáze
        $participant->delete();

        // Přesměruji zpět s potvrzením, že byl účastník smazán
        return redirect()->back()->with('success', 'Účastník byl smazán.');
    }
}
