<!DOCTYPE html>
{{-- Nastavím jazyk stránky na češtinu --}}
<html lang="cs">
<head>
    {{-- Nastavím znakovou sadu UTF-8 pro správnou diakritiku --}}
    <meta charset="UTF-8">
    {{-- Nastavím responzivní zobrazení pro mobily, tablety i počítače --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Titulek stránky v záložce prohlížeče --}}
    <title>Kurzy a přihlašování</title>
    {{-- Načtu Tailwind CSS přes CDN pro stylování rozhraní --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
{{-- Tělo stránky s pozadím, barvou textu a vyhlazeným písmem --}}
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-slate-50 to-purple-50 text-slate-800 antialiased">
{{-- Hlavní kontejner vycentrovaný na střed s mezerami mezi sekcemi --}}
<div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

    <!-- Hlavička -->
    <header class="bg-white/90 backdrop-blur border border-indigo-100 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            {{-- Horní štítek sekce --}}
            <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 mb-2">
                    Správa výuky & přihlášek
                </span>
            {{-- Hlavní nadpis aplikace --}}
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Nabídka kurzů a přihlašování účastníků</h1>
            {{-- Krátký popis funkčnosti --}}
            <p class="text-sm text-slate-500 mt-1">Přehled termínů, volných míst, účastníků a stavů přihlášek na jednom místě.</p>
        </div>
        {{-- Pravý panel se souhrnnými počty --}}
        <div class="flex gap-3 text-center">
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-2.5">
                {{-- Vypíšu celkový počet načtených kurzů --}}
                <div class="text-lg font-bold text-indigo-700">{{ $courses->count() }}</div>
                <div class="text-xs text-slate-500">Kurzů</div>
            </div>
            <div class="bg-purple-50 border border-purple-100 rounded-xl px-4 py-2.5">
                {{-- Vypíšu celkový počet účastníků --}}
                <div class="text-lg font-bold text-purple-700">{{ $participants->count() }}</div>
                <div class="text-xs text-slate-500">Účastníků</div>
            </div>
        </div>
    </header>

    <!-- Hlášky o úspěchu a chybách -->
    {{-- Pokud je v session zpráva 'success', zobrazím zelený potvrzovací box --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl shadow-sm text-sm font-medium">
            {{-- Vypíšu text úspěšné hlášky --}}
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Pokud došlo k validačním nebo logickým chybám, vypíšu jejich seznam --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl shadow-sm text-sm">
            <div class="font-semibold mb-1">Něco se nepovedlo:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                {{-- Projdu všechny chybové hlášky --}}
                @foreach($errors->all() as $error)
                    {{-- Vypíšu jednu konkrétní chybu --}}
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formuláře: Nový kurz, Nový účastník, Přihláška -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Nový kurz -->
        {{-- Formulář odešle POST požadavek na routu courses.store --}}
        <form action="{{ route('courses.store') }}" method="POST" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3.5 flex flex-col justify-between">
            {{-- CSRF token pro ochranu formuláře v Laravelu --}}
            @csrf
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <h2 class="font-semibold text-base text-slate-900">Nový kurz</h2>
                </div>
                {{-- Pole pro název kurzu --}}
                <input type="text" name="name" placeholder="Název kurzu" required
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm transition">
                {{-- Pole pro unikátní kód kurzu --}}
                <input type="text" name="code" placeholder="Kód kurzu (např. KURZ-01)" required
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm font-mono transition">
                {{-- Pole pro maximální kapacitu kurzu (minimálně 1) --}}
                <input type="number" name="capacity" placeholder="Kapacita (např. 15)" min="1" required
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm transition">
                {{-- Pole pro datum a čas začátku kurzu --}}
                <input type="datetime-local" name="starts_at" required
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm transition">
            </div>
            {{-- Tlačítko pro uložení nového kurzu --}}
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-sm font-semibold shadow-sm transition cursor-pointer">
                + Uložit kurz
            </button>
        </form>

        <!-- Nový účastník -->
        {{-- Formulář odešle POST požadavek na routu participants.store --}}
        <form action="{{ route('participants.store') }}" method="POST" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3.5 flex flex-col justify-between">
            {{-- CSRF bezpečnostní token --}}
            @csrf
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-violet-500"></span>
                    <h2 class="font-semibold text-base text-slate-900">Nový účastník</h2>
                </div>
                {{-- Pole pro jméno a příjmení účastníka --}}
                <input type="text" name="name" placeholder="Jméno a příjmení" required
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400 text-sm transition">
                {{-- Pole pro unikátní e-mail účastníka --}}
                <input type="email" name="email" placeholder="E-mailová adresa" required
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-400 text-sm transition">
            </div>
            {{-- Tlačítko pro přidání účastníka --}}
            <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white py-2.5 rounded-xl text-sm font-semibold shadow-sm transition cursor-pointer">
                + Přidat účastníka
            </button>
        </form>

        <!-- Přihlášení do kurzu -->
        {{-- Formulář pro přihlášení existujícího účastníka na vybraný kurz --}}
        <form action="{{ route('enrollments.store') }}" method="POST" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3.5 flex flex-col justify-between">
            {{-- CSRF bezpečnostní token --}}
            @csrf
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-fuchsia-500"></span>
                    <h2 class="font-semibold text-base text-slate-900">Přihlásit účastníka</h2>
                </div>
                {{-- Roletka pro výběr účastníka --}}
                <select name="participant_id" required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-400 text-sm transition">
                    <option value="">-- Vyber účastníka --</option>
                    {{-- Vypíšu všechny účastníky jako možnosti ve výběru --}}
                    @foreach($participants as $participant)
                        <option value="{{ $participant->id }}">{{ $participant->name }} ({{ $participant->email }})</option>
                    @endforeach
                </select>
                {{-- Roletka pro výběr kurzu --}}
                <select name="course_id" required
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-400 text-sm transition">
                    <option value="">-- Vyber kurz --</option>
                    {{-- Vypíšu všechny kurzy jako možnosti ve výběru --}}
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->code }} – {{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Tlačítko pro odeslání přihlášky --}}
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-2.5 rounded-xl text-sm font-semibold shadow-sm transition cursor-pointer">
                Přihlásit do kurzu
            </button>
        </form>
    </section>

    <!-- Filtry -->
    {{-- GET formulář pro filtrování kurzů podle data začátku a volné kapacity --}}
    <form action="{{ route('home') }}" method="GET" class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex flex-wrap items-end gap-4 text-sm">
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Termín od</label>
            {{-- Zachovám aktuálně zvolený filtr starts_from z URL --}}
            <input type="date" name="starts_from" value="{{ request('starts_from') }}"
                   class="px-3 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <label class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer select-none">
            {{-- Zaškrtávátko pro zobrazení pouze kurzů, kde není plná kapacita --}}
            <input type="checkbox" name="only_free" value="1" {{ request('only_free') ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
            <span class="text-slate-700 font-medium">Pouze kurzy s volným místem</span>
        </label>
        <div class="flex items-center gap-3">
            {{-- Tlačítko pro použití filtrů --}}
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-medium transition cursor-pointer">
                Filtrovat
            </button>
            {{-- Odkaz pro resetování filtrů zpět na výchozí přehled --}}
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800 text-xs font-medium underline">
                Zrušit filtr
            </a>
        </div>
    </form>

    <!-- Seznam kurzů -->
    <section class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900 px-1">Přehled kurzů</h2>

        {{-- Projdu všechny kurzy; pokud žádný není, vykoná se blok @empty --}}
        @forelse($courses as $course)
            {{-- Spočítám aktivní přihlášky a zjistím, jestli je kurz plný --}}
            @php
                // Počet přihlášek ve stavu 'enrolled'
                $activeCount = $course->activeEnrollmentsCount();
                // Pravdivostní hodnota, zda je kapacita naplněna nebo překročena
                $isFull = $activeCount >= $course->capacity;
            @endphp
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3.5">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Kód kurzu --}}
                            <span class="font-mono text-xs font-bold px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $course->code }}
                                </span>
                            {{-- Název kurzu --}}
                            <h3 class="text-lg font-bold text-slate-900">{{ $course->name }}</h3>
                            {{-- Podle obsazenosti zobrazím červený nebo zelený štítek --}}
                            @if($isFull)
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700">Obsazeno</span>
                            @else
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Volná místa</span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 flex flex-wrap gap-x-4 gap-y-1">
                            {{-- Datum a čas začátku kurzu --}}
                            <span>Začátek: <strong class="text-slate-700">{{ $course->starts_at }}</strong></span>
                            {{-- Aktuální obsazenost vůči celkové kapacitě --}}
                            <span>Obsazenost: <strong class="text-slate-700">{{ $activeCount }} / {{ $course->capacity }}</strong></span>
                        </div>
                    </div>

                    {{-- Formulář pro smazání kurzu --}}
                    <form action="{{ route('courses.destroy', $course) }}" method="POST">
                        {{-- CSRF ochrana --}}
                        @csrf
                        {{-- Přepíšu HTTP metodu na DELETE pro RESTful smazání --}}
                        @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition cursor-pointer">
                            Smazat kurz
                        </button>
                    </form>
                </div>

                <!-- Přihlášení účastníci -->
                <div class="space-y-2">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Přihlášení účastníci</div>

                    {{-- Projdu všechny přihlášky daného kurzu --}}
                    @forelse($course->enrollments as $enrollment)
                        {{-- Vyberu barvu štítku podle aktuálního stavu přihlášky --}}
                        @php
                            $badgeClasses = match($enrollment->status) {
                                'enrolled' => 'bg-sky-100 text-sky-800 border-sky-200',
                                'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-slate-50/80 border border-slate-100 rounded-xl px-3.5 py-2.5 text-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Jméno účastníka --}}
                                <span class="font-medium text-slate-800">{{ $enrollment->participant->name ?? 'Účastník' }}</span>
                                {{-- E-mail účastníka --}}
                                <span class="text-xs text-slate-500">({{ $enrollment->participant->email ?? '' }})</span>
                                {{-- Štítek s aktuálním stavem přihlášky --}}
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $badgeClasses }}">
                                        {{ $enrollment->status }}
                                    </span>
                            </div>

                            {{-- Formulář pro změnu stavu přihlášky --}}
                            <form action="{{ route('enrollments.status', $enrollment) }}" method="POST" class="flex items-center gap-2">
                                {{-- CSRF ochrana --}}
                                @csrf
                                {{-- Přepíšu metodu na PATCH pro aktualizaci stavu --}}
                                @method('PATCH')
                                {{-- Výběr nového stavu přihlášky --}}
                                <select name="status" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <option value="enrolled" {{ $enrollment->status === 'enrolled' ? 'selected' : '' }}>enrolled</option>
                                    <option value="cancelled" {{ $enrollment->status === 'cancelled' ? 'selected' : '' }}>cancelled</option>
                                    <option value="completed" {{ $enrollment->status === 'completed' ? 'selected' : '' }}>completed</option>
                                </select>
                                {{-- Tlačítko pro uložení změněného stavu --}}
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition cursor-pointer">
                                    Uložit stav
                                </button>
                            </form>
                        </div>
                    @empty
                        {{-- Hláška, pokud na kurz zatím nikdo není přihlášen --}}
                        <p class="text-xs text-slate-400 italic py-1">Zatím nikdo přihlášený.</p>
                    @endforelse
                </div>
            </div>
        @empty
            {{-- Hláška, pokud filtrům neodpovídá žádný kurz --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 text-center text-sm text-slate-500 shadow-sm">
                Žádné kurzy neodpovídají aktuálnímu filtru.
            </div>
        @endforelse
    </section>

    <!-- Seznam účastníků -->
    <section class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
        <h2 class="text-lg font-bold text-slate-900">Seznam účastníků</h2>

        {{-- Projdu všechny účastníky v systému --}}
        @forelse($participants as $participant)
            <div class="flex items-center justify-between gap-2 py-2 border-b border-slate-100 last:border-b-0 text-sm">
                <div>
                    {{-- Jméno účastníka --}}
                    <span class="font-medium text-slate-800">{{ $participant->name }}</span>
                    <span class="text-slate-400 mx-1.5">•</span>
                    {{-- E-mail účastníka --}}
                    <span class="text-slate-500 text-xs">{{ $participant->email }}</span>
                </div>
                {{-- Formulář pro smazání účastníka --}}
                <form action="{{ route('participants.destroy', $participant) }}" method="POST">
                    {{-- CSRF ochrana --}}
                    @csrf
                    {{-- Přepíšu metodu na DELETE --}}
                    @method('DELETE')
                    <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-800 hover:underline cursor-pointer">
                        Smazat
                    </button>
                </form>
            </div>
        @empty
            {{-- Hláška, pokud v databázi ještě není žádný účastník --}}
            <p class="text-xs text-slate-400 italic">Zatím žádní účastníci.</p>
        @endforelse
    </section>

</div>
</body>
</html>
