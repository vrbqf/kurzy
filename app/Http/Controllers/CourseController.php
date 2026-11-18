<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code',
            'capacity' => 'required|integer|min:1',
            'starts_at' => 'required|date',
        ]);

        Course::create($validated);

        return redirect()->back()->with('success', 'Kurz byl vytvořen.');
    }

    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }
}
