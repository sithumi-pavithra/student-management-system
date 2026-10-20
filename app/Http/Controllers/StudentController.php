<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = $this->filteredQuery($request)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $courses = Student::query()->distinct()->orderBy('course')->pluck('course');

        $stats = [
            'total'     => Student::count(),
            'active'    => Student::where('status', Student::STATUS_ACTIVE)->count(),
            'inactive'  => Student::where('status', Student::STATUS_INACTIVE)->count(),
            'suspended' => Student::where('status', Student::STATUS_SUSPENDED)->count(),
            'graduated' => Student::where('status', Student::STATUS_GRADUATED)->count(),
            'trashed'   => Student::onlyTrashed()->count(),
        ];

        return view('students.index', compact('students', 'courses', 'stats'));
    }

    public function export(Request $request): StreamedResponse
    {
        $students = $this->filteredQuery($request)->orderBy('name')->get();

        return response()->streamDownload(function () use ($students) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['Name', 'Email', 'Phone', 'Course', 'Status', 'Address']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->name,
                    $student->email,
                    $student->phone,
                    $student->course,
                    $student->status,
                    $student->address,
                ]);
            }

            fclose($file);
        }, 'students.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('students', 'public');
        }

        Student::create($validated);

        return $this->respond($request, 'Student added successfully!');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate($this->rules($id));

        if ($request->hasFile('image')) {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }

            $validated['image'] = $request->file('image')->store('students', 'public');
        }

        $student->update($validated);

        return $this->respond($request, 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student moved to trash. Status: ' . ucfirst($student->status));
    }

    public function trash()
    {
        $students = Student::onlyTrashed()->latest()->get();

        return view('students.trash', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('students.trash')
            ->with('success', 'Student restored! Status: ' . ucfirst($student->status));
    }

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

    /* ----------------------------------------------------------------
     |  Helpers
     | ---------------------------------------------------------------- */

    /**
     * Search + course + status filters (shared by index and export).
     */
    private function filteredQuery(Request $request)
    {
        $query = Student::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($course = $request->input('course')) {
            $query->where('course', $course);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Validation rules (shared by store and update).
     */
    private function rules($ignoreId = null): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:students,email' . ($ignoreId ? ',' . $ignoreId : ''),
            'phone'   => 'required|digits:10',
            'course'  => 'required|string|max:255',
            'status'  => 'required|in:active,inactive,graduated,suspended',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
        ];
    }

    /**
     * AJAX request  -> JSON (flash message is shown after the JS reloads the page)
     * Normal request -> redirect back to the list.
     */
    private function respond(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            session()->flash('success', $message);

            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('students.index')->with('success', $message);
    }
}