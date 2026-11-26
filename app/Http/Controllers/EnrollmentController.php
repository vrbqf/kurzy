<?php

// Jmenný prostor pro kontrolery v Laravelu
namespace App\Http\Controllers;

// Načtu model Course, abych mohla pracovat s kurzy a jejich kapacitou
use App\Models\Course;
// Načtu model Enrollment, který představuje přihlášku do kurzu
use App\Models\Enrollment;
// Načtu třídu Request pro práci s daty odeslanými z formuláře
use Illuminate\Http\Request;

// Kontroler starající se o přihlášky a jejich stavy
class EnrollmentController extends Controller
{
    // Metoda pro vytvoření nové přihlášky do kurzu
    public function store(Request $request)
    {
        // Ověřím, že přišla správná data z formuláře
        $validated = $request->validate([
            // ID kurzu je povinné a musí existovat v tabulce courses
            'course_id' => 'required|exists:courses,id',
            // ID účastníka je povinné a musí existovat v tabulce participants
            'participant_id' => 'required|exists:participants,id',
        ]);

        // Najdu daný kurz v databázi, nebo vyhodím 404, kdyby neexistoval
        $course = Course::findOrFail($validated['course_id']);

        // Hledám v přihláškách záznam pro tento kurz
        $exists = Enrollment::where('course_id', $course->id)
            // a pro tohoto konkrétního účastníka
            ->where('participant_id', $validated['participant_id'])
            // kde má účastník právě aktivně zapsaný stav 'enrolled'
            ->where('status', 'enrolled')
            // Vrátí true/false, jestli taková aktivní přihláška už existuje
            ->exists();

        // Pokud už účastník v kurzu aktivně je
        if ($exists) {
            // Vrátím ho zpět s chybovou hláškou
            return back()->withErrors(['error' => 'Účastník je již do tohoto kurzu přihlášen.']);
        }

        // Kontrola kapacity kurzu: porovnám počet aktuálně obsazených míst s maximální kapacitou kurzu
        if ($course->activeEnrollmentsCount() >= $course->capacity) {
            // Pokud už není volné místo, nepustím ho dál a vypíšu chybu
            return back()->withErrors(['error' => 'Kurz je již plně obsazen.']);
        }

        // Vytvoření přihlášky ve stavu 'enrolled': zapíšu novou přihlášku do databáze
        Enrollment::create([
            // Propojím přihlášku s kurzem
            'course_id' => $course->id,
            // Propojím přihlášku s účastníkem
            'participant_id' => $validated['participant_id'],
            // Uložím aktuální datum a čas přihlášení
            'enrolled_at' => now(),
            // Nastavím výchozí stav na 'enrolled' (aktivně přihlášen)
            'status' => 'enrolled',
        ]);

        // Přesměruji zpět na stránku se zprávou o úspěchu
        return back()->with('success', 'Účastník byl úspěšně přihlášen.');
    }

    // Metoda pro změnu stavu existující přihlášky
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        // Zkontroluji, že nový stav je platný
        $validated = $request->validate([
            // Povolené jsou pouze tyto tři stavy ze zadání
            'status' => 'required|in:enrolled,cancelled,completed',
        ]);

        // Vezmu kurz, ke kterému tato přihláška patří
        $course = $enrollment->course;
        // Uložím si nový požadovaný stav do proměnné
        $newStatus = $validated['status'];

        // Zjistím, jestli přihláška dříve zabírala místo (měla stav 'enrolled')
        $wasActive = $enrollment->status === 'enrolled';
        // Zjistím, jestli bude místo zabírat nově (bude mít stav 'enrolled')
        $willBeActive = $newStatus === 'enrolled';

        // Když chci přihlášku znovu aktivovat (třeba z cancelled/completed) a kurz už je plný
        if (! $wasActive && $willBeActive && $course->activeEnrollmentsCount() >= $course->capacity) {
            // Zablokuji změnu a vrátím chybovou hlášku
            return back()->withErrors(['error' => 'Kurz je již plně obsazen, nelze aktivovat přihlášku.']);
        }

        // Aktualizuji přihlášku v databázi
        $enrollment->update([
            // Zapíšu nový stav
            'status' => $newStatus,
        ]);

        // Vrátím se zpět s potvrzením, že se stav změnil
        return back()->with('success', 'Stav přihlášky byl aktualizován.');
    }
}
