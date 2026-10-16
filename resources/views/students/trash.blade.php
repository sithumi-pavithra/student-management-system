@extends('layouts.app')

@section('title', 'Trash - Student Management')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Trash</h1>
            <p class="text-muted mb-0">Deleted students can be restored from here</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-trash text-muted"></i>
                <h2 class="h5 mb-0">Deleted Students</h2>
                <span class="badge bg-danger">{{ $students->count() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $student->name }}</div>
                                    <div class="small text-muted">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge rounded-pill text-bg-secondary">{{ $student->course }}</span></td>
                        <td>
                            <span class="badge rounded-pill {{ $student->overall_status_badge_class }}">
                                {{ $student->overall_status_label }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                {{ $student->deleted_at->format('Y-m-d H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                {{-- RESTORE --}}
                                <form method="POST"
                                      action="{{ route('students.restore', $student->id) }}"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>

                                {{-- FORCE DELETE --}}
                                <form method="POST"
                                      action="{{ route('students.forceDelete', $student->id) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Permanently delete? Cannot be undone!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete Forever
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <p class="text-muted fw-medium mb-0">No deleted students</p>
                            <small class="text-muted">Deleted students will appear here</small>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection