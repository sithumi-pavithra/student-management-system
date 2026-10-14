<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // STUDENT PROFILE
    public function show($id)
    {
        $student = Student::findOrFail($id);

        return view('students.show', compact('student'));
    }
    //  LIST
    public function index(Request $request)
    {
        $query = Student::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Course filter
        if ($course = $request->input('course')) {
            $query->where('course', $course);
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $courses  = Student::query()->distinct()->orderBy('course')->pluck('course');

        // Stats for dashboard
        $stats = [
            'total'     => Student::count(),
            'active'    => Student::where('status', 'active')->count(),
            'inactive'  => Student::where('status', 'inactive')->count(),
            'suspended' => Student::where('status', 'suspended')->count(),
            'graduated' => Student::where('status', 'graduated')->count(),
            'trashed'   => Student::onlyTrashed()->count(),
        ];

        $courseStats = Student::query()
            ->selectRaw('course, COUNT(*) as total')
            ->groupBy('course')
            ->orderBy('course')
            ->get();

        return view('students.index', compact('students', 'courses', 'stats', 'courseStats'));
    }

    // STORE (from modal)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:students,email',
            'phone'   => 'required|string|max:10',
            'course'  => 'required|string|max:255',
            'status'  => 'required|in:active,inactive,graduated,suspended',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('students', 'public');
        }

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    //  UPDATE (from modal)
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:students,email,' . $id,
            'phone'   => 'required|string|max:10',
            'course'  => 'required|string|max:255',
            'status'  => 'required|in:active,inactive,graduated,suspended',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $validated['image'] = $request->file('image')->store('students', 'public');
        }

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    // SOFT DELETE 
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', "Student moved to trash. Status: " . ucfirst($student->status));
    }

    // TRASH LIST 
    public function trash()
    {
        $students = Student::onlyTrashed()->latest()->get();
        return view('students.trash', compact('students'));
    }

    // RESTORE
    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('students.trash')
            ->with('success', "Student restored! Status: " . ucfirst($student->status));
    }

    //  FORCE DELETE
    public function forceDelete($id)
    {
        $student = Student::withTrashed()->findOrFail($id);

        if ($student->image) {
            Storage::disk('public')->delete($student->image);
        }

        $student->forceDelete();

        return redirect()->route('students.trash')
            ->with('success', 'Student permanently deleted!');
    }
}