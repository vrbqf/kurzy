<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Seznam kurzů</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-6 text-slate-800">
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold">Nabídka kurzů</h1>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 p-4 rounded border border-emerald-200">{{ session('success') }}</div>
    @endif

    <!-- Formulář pro přidání kurzu -->
    <form action="{{ route('courses.store') }}" method="POST" class="bg-white p-4 rounded shadow space-y-3">
        @csrf
        <h2 class="font-semibold">Přidat nový kurz</h2>
        <input type="text" name="name" placeholder="Název kurzu" required class="w-full p-2 border rounded text-sm">
        <input type="text" name="code" placeholder="Kód (např. KURZ-1)" required class="w-full p-2 border rounded text-sm uppercase">
        <input type="number" name="capacity" placeholder="Kapacita (>0)" min="1" required class="w-full p-2 border rounded text-sm">
        <input type="datetime-local" name="starts_at" required class="w-full p-2 border rounded text-sm">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-medium">Uložit kurz</button>
    </form>

    <!-- Výpis kurzů -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 border-b text-xs uppercase text-slate-600">
            <tr>
                <th class="p-3">Kód</th>
                <th class="p-3">Název</th>
                <th class="p-3">Kapacita</th>
                <th class="p-3">Začátek</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @foreach($courses ?? [] as $course)
                <tr>
                    <td class="p-3 font-mono font-bold">{{ $course->code }}</td>
                    <td class="p-3">{{ $course->name }}</td>
                    <td class="p-3">{{ $course->capacity }}</td>
                    <td class="p-3">{{ $course->starts_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
