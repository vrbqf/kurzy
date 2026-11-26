<?php

// Jmenný prostor pro kontrolery v Laravelu
namespace App\Http\Controllers;

// Načtu model Course pro práci s kurzy v databázi
use App\Models\Course;
// Načtu model Participant pro načtení seznamu účastníků
use App\Models\Participant;
// Načtu třídu Request pro čtení parametrů z URL a dat z formulářů
use Illuminate\Http\Request;

// Kontroler pro správu kurzů, filtrování a zobrazení hlavní stránky
class CourseController extends Controller
{
    // Metoda pro načtení a vypsání přehledu kurzů a účastníků na hlavní stránce
    public function index(Request $request)
    {
        // Připravím dotaz na kurzy, přednačtu jejich přihlášky i účastníky a seřadím podle data začátku
        $query = Course::with(['enrollments.participant'])->orderBy('starts_at');

        // Filtr podle termínu (od zadaného data): pokud je parametr starts_from vyplněný
        if ($request->filled('starts_from')) {
            // Vyberu jen kurzy, které začínají daný den nebo později
            $query->whereDate('starts_at', '>=', $request->input('starts_from'));
        }

        // Spustím SQL dotaz a získám kolekci kurzů
        $courses = $query->get();

        // Filtr podle volných míst: pokud je zaškrtnutý filtr only_free
        if ($request->boolean('only_free')) {
            // Ponechám v kolekci jen kurzy, kde je počet aktivních přihlášek menší než kapacita
            $courses = $courses->filter(function ($course) {
                // Vrátí true, pokud má kurz ještě volné místo
                return $course->activeEnrollmentsCount() < $course->capacity;
                // Přeindexuji klíče kolekce, aby byly hezky po sobě (0, 1, 2...)
            })->values();
        }

        // Načtu všechny účastníky abecedně podle jména
        $participants = Participant::orderBy('name')->get();

        // Vrátím pohled courses.index a předám do něj proměnné $courses a $participants
        return view('courses.index', compact('courses', 'participants'));
    }

    // Metoda pro vytvoření a uložení nového kurzu
    public function store(Request $request)
    {
        // Ověřím, že zadané údaje o kurzu splňují všechna pravidla
        $validated = $request->validate([
            // Název je povinný text do 255 znaků
            'name' => 'required|string|max:255',
            // Kód je povinný text do 50 znaků a musí být unikátní v tabulce courses
            'code' => 'required|string|max:50|unique:courses,code',
            // Kapacita je povinné celé číslo minimálně 1
            'capacity' => 'required|integer|min:1',
            // Datum začátku je povinné a musí mít platný formát data
            'starts_at' => 'required|date',
        ]);

        // Vytvořím nový záznam kurzu v databázi podle zvalidovaných dat
        Course::create($validated);

        // Přesměruji zpět na předchozí stránku se zprávou o úspěchu
        return redirect()->back()->with('success', 'Kurz byl vytvořen.');
    }

    // Metoda pro smazání vybraného kurzu
    public function destroy(Course $course)
    {
        // Smažu záznam kurzu z databáze
        $course->delete();

        // Přesměruji zpět na předchozí stránku s potvrzením o smazání
        return redirect()->back()->with('success', 'Kurz byl smazán.');
    }
}
