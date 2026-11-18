<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'participant_id' => 'required|exists:participants,id',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        // 1. Kontrola, zda už účastník není přihlášen
        $exists = Enrollment::where('course_id', $course->id)
            ->where('participant_id', $validated['participant_id'])
            ->whereIn('status', ['enrolled', 'completed'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Účastník je již do tohoto kurzu přihlášen.']);
        }

        // 2. Kontrola kapacity kurzu
        if ($course->activeEnrollmentsCount() >= $course->capacity) {
            return back()->withErrors(['error' => 'Kurz je již plně obsazen.']);
        }

        // Vytvoření přihlášky ve stavu 'enrolled'
        Enrollment::create([
            'course_id' => $course->id,
            'participant_id' => $validated['participant_id'],
            'enrolled_at' => now(),
            'status' => 'enrolled',
        ]);

        return back()->with('success', 'Účastník byl úspěšně přihlášen.');
    }
}
