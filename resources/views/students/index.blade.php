@extends('layouts.app')

@section('title', 'Students List - Student Management')

@php
    $statuses = [
        'active'    => 'Active',
        'inactive'  => 'Inactive',
        'graduated' => 'Graduated',
        'suspended' => 'Suspended',
    ];

    $cards = [
        ['Total',     $stats['total'],     'primary', 'fa-users'],
        ['Active',    $stats['active'],    'success', 'fa-user-check'],
        ['Graduated', $stats['graduated'], 'info',    'fa-graduation-cap'],
        ['Trashed',   $stats['trashed'],   'danger',  'fa-trash'],
    ];
@endphp

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Students List</h1>
            <p class="text-muted mb-0">Manage all registered students</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('students.trash') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-trash"></i> Trash
                @if($stats['trashed'] > 0)
                    <span class="badge bg-light text-dark">{{ $stats['trashed'] }}</span>
                @endif
            </a>
            <button type="button" class="btn btn-primary btn-sm"
                    data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="fas fa-plus"></i> Add New Student
            </button>
            <a href="{{ route('students.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        @foreach($cards as [$label, $value, $color, $icon])
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted text-uppercase fw-semibold">{{ $label }}</small>
                            <div class="fs-3 fw-bold text-{{ $color }}">{{ $value }}</div>
                        </div>
                        <div class="bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded d-flex align-items-center justify-content-center"
                             style="width:40px;height:40px">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- SEARCH + FILTER + TABLE --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex flex-column flex-lg-row justify-content-between gap-3 py-3">
            <div>
                <h2 class="h5 mb-0">All Students</h2>
                <small class="text-muted">{{ $students->total() }} registered students</small>
            </div>

            <form method="GET" action="{{ route('students.index') }}"
                  class="d-flex flex-column flex-sm-row gap-2">
                <input type="search" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Search name, email, phone">

                <select name="course" class="form-select form-select-sm">
                    <option value="">All courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course }}" @selected(request('course') === $course)>{{ $course }}</option>
                    @endforeach
                </select>

                <select name="status" class="form-select form-select-sm">
                    <option value="">All statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary btn-sm">Apply</button>

                @if(request()->filled('search') || request()->filled('course') || request()->filled('status'))
                    <a href="{{ route('students.index') }}" class="btn btn-link btn-sm text-decoration-none">Clear</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Contact</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="text-muted">{{ $students->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($student->image)
                                    <img src="{{ asset('storage/' . $student->image) }}"
                                         class="rounded-circle object-fit-cover"
                                         style="width:40px;height:40px" alt="{{ $student->name }}">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-semibold"
                                         style="width:40px;height:40px">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    {{-- Name click → View Modal --}}
                                    <button type="button"
                                            class="btn btn-link p-0 fw-semibold text-decoration-none view-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewStudentModal"
                                            data-id="{{ $student->id }}"
                                            data-name="{{ $student->name }}"
                                            data-email="{{ $student->email }}"
                                            data-phone="{{ $student->phone }}"
                                            data-course="{{ $student->course }}"
                                            data-status="{{ $student->status }}"
                                            data-status-label="{{ $student->status_label }}"
                                            data-status-badge="{{ $student->status_badge_class }}"
                                            data-address="{{ $student->address }}"
                                            data-image="{{ $student->image ? asset('storage/' . $student->image) : '' }}"
                                            data-created="{{ $student->created_at->format('M d, Y') }}">
                                        {{ $student->name }}
                                    </button>
                                    <div class="small text-muted">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->phone }}</td>
                        <td><span class="badge rounded-pill text-bg-primary">{{ $student->course }}</span></td>
                        <td>
                            <span class="badge rounded-pill {{ $student->status_badge_class }}">
                                {{ $student->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                {{-- VIEW --}}
                                <button type="button" class="btn btn-outline-info btn-sm view-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewStudentModal"
                                        data-id="{{ $student->id }}"
                                        data-name="{{ $student->name }}"
                                        data-email="{{ $student->email }}"
                                        data-phone="{{ $student->phone }}"
                                        data-course="{{ $student->course }}"
                                        data-status="{{ $student->status }}"
                                        data-status-label="{{ $student->status_label }}"
                                        data-status-badge="{{ $student->status_badge_class }}"
                                        data-address="{{ $student->address }}"
                                        data-image="{{ $student->image ? asset('storage/' . $student->image) : '' }}"
                                        data-created="{{ $student->created_at->format('M d, Y') }}"
                                        title="View">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- EDIT --}}
                                <button type="button" class="btn btn-outline-primary btn-sm edit-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStudentModal"
                                        data-id="{{ $student->id }}"
                                        data-name="{{ $student->name }}"
                                        data-email="{{ $student->email }}"
                                        data-phone="{{ $student->phone }}"
                                        data-course="{{ $student->course }}"
                                        data-status="{{ $student->status }}"
                                        data-address="{{ $student->address }}"
                                        data-image="{{ $student->image ? asset('storage/' . $student->image) : '' }}"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- DELETE --}}
                                <form method="POST"
                                      action="{{ route('students.destroy', $student->id) }}"
                                      onsubmit="return confirm('Move to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <p class="text-muted fw-medium mb-0">No students found</p>
                            <small class="text-muted">Click "Add New Student" to get started</small>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</div>

<div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle me-2"></i>Student Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Profile --}}
                <div class="text-center mb-4">
                    <div id="viewImageWrap">
                        <img id="viewImage" src="" alt="Student"
                             class="rounded-circle object-fit-cover border border-3 border-info"
                             style="width:100px;height:100px">
                    </div>
                    <div id="viewInitialWrap" class="d-none">
                        <div id="viewInitial"
                             class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center fw-bold"
                             style="width:100px;height:100px;font-size:2.5rem">
                            A
                        </div>
                    </div>
                    <h4 id="viewName" class="mt-3 mb-1 fw-bold">Student Name</h4>
                    <p id="viewEmail" class="text-muted small mb-2">student@example.com</p>
                    <span id="viewStatus" class="badge rounded-pill">Status</span>
                </div>

                {{-- Details --}}
                <div>
                    <div class="student-detail-item">
                        <span class="label"><i class="fas fa-phone"></i>Phone</span>
                        <span id="viewPhone" class="value">-</span>
                    </div>

                    <div class="student-detail-item">
                        <span class="label"><i class="fas fa-book"></i>Course</span>
                        <span id="viewCourse" class="value">-</span>
                    </div>

                    <div class="student-detail-item">
                        <span class="label"><i class="fas fa-map-marker-alt"></i>Address</span>
                        <span id="viewAddress" class="value text-end" style="max-width:60%">-</span>
                    </div>

                    <div class="student-detail-item">
                        <span class="label"><i class="far fa-calendar"></i>Registered</span>
                        <span id="viewCreated" class="value">-</span>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn btn-primary" id="viewEditBtn">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Add New Student
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    @if($errors->any() && !old('_method'))
                        <div class="alert alert-danger">
                            <strong><i class="fas fa-exclamation-circle me-1"></i>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-user text-primary me-1"></i> Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control" placeholder="Enter student name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-envelope text-primary me-1"></i> Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="student@example.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-phone text-primary me-1"></i> Phone *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="form-control" placeholder="0712345678"
                                   pattern="[0-9]{10}" maxlength="10" inputmode="numeric"
                                   title="Please enter exactly 10 digits" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-book text-primary me-1"></i> Course *</label>
                            <input type="text" name="course" value="{{ old('course') }}"
                                   class="form-control" placeholder="e.g., HNDIT, HNDA" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-flag text-primary me-1"></i> Status *</label>
                            <select name="status" class="form-select" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'active') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-image text-primary me-1"></i> Profile Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">JPG, PNG up to 2MB</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-map-marker-alt text-primary me-1"></i> Address</label>
                            <textarea name="address" rows="3" class="form-control"
                                      placeholder="Enter address">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Student
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="editStudentForm" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div id="editCurrentImage" class="d-none mb-3">
                        <label class="form-label"><i class="fas fa-image text-primary me-1"></i> Current Image</label>
                        <div>
                            <img id="editCurrentImagePreview" src="" alt="Current image"
                                 class="rounded-circle object-fit-cover" style="width:80px;height:80px">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-user text-primary me-1"></i> Full Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-envelope text-primary me-1"></i> Email *</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-phone text-primary me-1"></i> Phone *</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control"
                                   pattern="[0-9]{10}" maxlength="10" inputmode="numeric"
                                   title="Please enter exactly 10 digits" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-book text-primary me-1"></i> Course *</label>
                            <input type="text" name="course" id="edit_course" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-flag text-primary me-1"></i> Status *</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-image text-primary me-1"></i> New Profile Image</label>
                            <input type="file" name="image" id="edit_imageInput" class="form-control" accept="image/*">
                            <div class="form-text">Leave empty to keep current</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-map-marker-alt text-primary me-1"></i> Address</label>
                            <textarea name="address" id="edit_address" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Student
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;

            document.getElementById('viewName').textContent = d.name;
            document.getElementById('viewEmail').textContent = d.email;
            document.getElementById('viewPhone').textContent = d.phone;
            document.getElementById('viewCourse').textContent = d.course;
            document.getElementById('viewAddress').textContent = d.address || 'No address provided';
            document.getElementById('viewCreated').textContent = d.created;

            // Status badge
            const statusEl = document.getElementById('viewStatus');
            statusEl.textContent = d.statusLabel;
            statusEl.className = 'badge rounded-pill ' + d.statusBadge;

            // Image or initial
            const imgWrap = document.getElementById('viewImageWrap');
            const initialWrap = document.getElementById('viewInitialWrap');

            if (d.image && d.image.trim() !== '') {
                document.getElementById('viewImage').src = d.image;
                imgWrap.classList.remove('d-none');
                initialWrap.classList.add('d-none');
            } else {
                document.getElementById('viewInitial').textContent = d.name.charAt(0).toUpperCase();
                imgWrap.classList.add('d-none');
                initialWrap.classList.remove('d-none');
            }

            // Store ID for Edit
            document.getElementById('viewEditBtn').dataset.id = d.id;
        });
    });

    //VIEW MODAL — Edit Button 
    document.getElementById('viewEditBtn').addEventListener('click', function () {
        const id = this.dataset.id;

        // Close view modal
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewStudentModal'));
        if (viewModal) viewModal.hide();

        // Open edit modal
        setTimeout(() => {
            const editBtn = document.querySelector('.edit-btn[data-id="' + id + '"]');
            if (editBtn) {
                editBtn.click();
            }
        }, 350);
    });

    // EDIT MODAL — Data Fill
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const s = btn.dataset;

            document.getElementById('edit_name').value = s.name;
            document.getElementById('edit_email').value = s.email;
            document.getElementById('edit_phone').value = s.phone;
            document.getElementById('edit_course').value = s.course;
            document.getElementById('edit_status').value = s.status;
            document.getElementById('edit_address').value = s.address || '';
            document.getElementById('edit_imageInput').value = '';
            document.getElementById('editStudentForm').action = `/students/${s.id}`;

            const wrap = document.getElementById('editCurrentImage');
            if (s.image) {
                document.getElementById('editCurrentImagePreview').src = s.image;
                wrap.classList.remove('d-none');
            } else {
                wrap.classList.add('d-none');
            }
        });
    });

  
    @if($errors->any() && !old('_method'))
        bootstrap.Modal.getOrCreateInstance(document.getElementById('addStudentModal')).show();
    @endif
});
</script>
@endpush