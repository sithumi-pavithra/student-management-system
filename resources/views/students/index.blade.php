
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

            {{-- Create button (same modal as Edit) --}}
            <button type="button" class="btn btn-primary btn-sm" id="createStudentBtn">
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
                    @php
                        // ALL student data lives in ONE attribute on the <tr>
                        $payload = [
                            'id'           => $student->id,
                            'name'         => $student->name,
                            'email'        => $student->email,
                            'phone'        => $student->phone,
                            'course'       => $student->course,
                            'status'       => $student->status,
                            'status_label' => $student->status_label,
                            'status_badge' => $student->status_badge_class,
                            'address'      => $student->address,
                            'image'        => $student->image ? asset('storage/' . $student->image) : '',
                            'created'      => $student->created_at->format('M d, Y'),
                        ];
                    @endphp
                    <tr data-student="{{ json_encode($payload) }}">
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
                                    <button type="button"
                                            class="btn btn-link p-0 fw-semibold text-decoration-none view-btn">
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
                                <button type="button" class="btn btn-outline-info btn-sm view-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="btn btn-outline-primary btn-sm edit-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

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

{{-- ============================================================
     VIEW MODAL
     ============================================================ --}}
<div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>Student Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="text-center mb-4">
                    <div id="viewImageWrap">
                        <img id="viewImage" src="" alt="Student"
                             class="rounded-circle object-fit-cover border border-3 border-info"
                             style="width:100px;height:100px">
                    </div>
                    <div id="viewInitialWrap" class="d-none">
                        <div id="viewInitial"
                             class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center fw-bold"
                             style="width:100px;height:100px;font-size:2.5rem">A</div>
                    </div>
                    <h4 id="viewName" class="mt-3 mb-1 fw-bold">Student Name</h4>
                    <p id="viewEmail" class="text-muted small mb-2">student@example.com</p>
                    <span id="viewStatus" class="badge rounded-pill">Status</span>
                </div>

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

{{-- ============================================================
     ONE MODAL FOR CREATE + EDIT  (AJAX submit)
     ============================================================ --}}
<div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="studentModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="studentForm" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="student_id" id="student_id">

                <div class="modal-body">

                    {{-- General (non-field) errors --}}
                    <div class="alert alert-danger d-none" id="formErrors"></div>

                    {{-- Current image (edit mode only) --}}
                    <div id="currentImageWrap" class="d-none mb-3">
                        <label class="form-label"><i class="fas fa-image text-primary me-1"></i> Current Image</label>
                        <div>
                            <img id="currentImagePreview" src="" alt="Current image"
                                 class="rounded-circle object-fit-cover" style="width:80px;height:80px">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="f_name"><i class="fas fa-user text-primary me-1"></i> Full Name *</label>
                            <input type="text" name="name" id="f_name" class="form-control"
                                   placeholder="Enter student name" required>
                            <div class="invalid-feedback" data-error-for="name"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="f_email"><i class="fas fa-envelope text-primary me-1"></i> Email *</label>
                            <input type="email" name="email" id="f_email" class="form-control"
                                   placeholder="student@example.com" required>
                            <div class="invalid-feedback" data-error-for="email"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="f_phone"><i class="fas fa-phone text-primary me-1"></i> Phone *</label>
                            <input type="text" name="phone" id="f_phone" class="form-control"
                                   placeholder="0712345678"
                                   pattern="[0-9]{10}" maxlength="10" inputmode="numeric"
                                   title="Please enter exactly 10 digits" required>
                            <div class="invalid-feedback" data-error-for="phone"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="f_course"><i class="fas fa-book text-primary me-1"></i> Course *</label>
                            <input type="text" name="course" id="f_course" class="form-control"
                                   placeholder="e.g., HNDIT, HNDA" required>
                            <div class="invalid-feedback" data-error-for="course"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="f_status"><i class="fas fa-flag text-primary me-1"></i> Status *</label>
                            <select name="status" id="f_status" class="form-select" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-error-for="status"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="f_image">
                                <i class="fas fa-image text-primary me-1"></i>
                                <span id="imageLabel">Profile Image</span>
                            </label>
                            <input type="file" name="image" id="f_image" class="form-control" accept="image/*">
                            <div class="form-text" id="imageHelp">JPG, PNG up to 2MB</div>
                            <div class="invalid-feedback" data-error-for="image"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="f_address"><i class="fas fa-map-marker-alt text-primary me-1"></i> Address</label>
                            <textarea name="address" id="f_address" rows="3" class="form-control"
                                      placeholder="Enter address"></textarea>
                            <div class="invalid-feedback" data-error-for="address"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> <span id="saveBtnText">Save Student</span>
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

    const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;
    const storeUrl    = @json(route('students.store'));
    const baseUrl     = @json(url('students'));      // /students

    const studentModalEl = document.getElementById('studentModal');
    const studentModal   = bootstrap.Modal.getOrCreateInstance(studentModalEl);
    const viewModalEl    = document.getElementById('viewStudentModal');
    const viewModal      = bootstrap.Modal.getOrCreateInstance(viewModalEl);

    const form        = document.getElementById('studentForm');
    const saveBtn     = document.getElementById('saveBtn');
    const saveBtnText = document.getElementById('saveBtnText');
    const generalErr  = document.getElementById('formErrors');

    let viewedStudent = null;

    // ---------- helpers ----------
    const studentFromRow = el => JSON.parse(el.closest('tr').dataset.student);

    function clearErrors() {
        generalErr.classList.add('d-none');
        generalErr.innerHTML = '';
        form.querySelectorAll('.is-invalid').forEach(i => i.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(d => d.textContent = '');
    }

    function showErrors(errors) {
        const general = [];
        Object.entries(errors).forEach(([field, messages]) => {
            const input = form.querySelector(`[name="${field}"]`);
            const box   = form.querySelector(`[data-error-for="${field}"]`);
            if (input && box) {
                input.classList.add('is-invalid');
                box.textContent = messages[0];
            } else {
                general.push(messages[0]);
            }
        });
        if (general.length) {
            generalErr.innerHTML = general.map(m => `<div>${m}</div>`).join('');
            generalErr.classList.remove('d-none');
        }
    }

    // ---------- open modal: CREATE or EDIT ----------
    function openStudentModal(student = null) {
        form.reset();
        clearErrors();

        const isEdit = student !== null;
        document.getElementById('student_id').value = isEdit ? student.id : '';

        document.getElementById('studentModalTitle').innerHTML = isEdit
            ? '<i class="fas fa-user-edit me-2"></i>Edit Student'
            : '<i class="fas fa-user-plus me-2"></i>Add New Student';

        saveBtnText.textContent = isEdit ? 'Update Student' : 'Save Student';
        document.getElementById('imageLabel').textContent = isEdit ? 'New Profile Image' : 'Profile Image';
        document.getElementById('imageHelp').textContent  = isEdit ? 'Leave empty to keep current' : 'JPG, PNG up to 2MB';

        const imgWrap = document.getElementById('currentImageWrap');

        if (isEdit) {
            form.name.value    = student.name;
            form.email.value   = student.email;
            form.phone.value   = student.phone;
            form.course.value  = student.course;
            form.status.value  = student.status;
            form.address.value = student.address || '';

            if (student.image) {
                document.getElementById('currentImagePreview').src = student.image;
                imgWrap.classList.remove('d-none');
            } else {
                imgWrap.classList.add('d-none');
            }
        } else {
            form.status.value = 'active';
            imgWrap.classList.add('d-none');
        }

        studentModal.show();
    }

    // Create button
    document.getElementById('createStudentBtn').addEventListener('click', () => openStudentModal());

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => openStudentModal(studentFromRow(btn)));
    });

    // Focus first field when modal is shown
    studentModalEl.addEventListener('shown.bs.modal', () => form.name.focus());

    // ---------- SUBMIT (AJAX) ----------
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        clearErrors();

        const id       = document.getElementById('student_id').value;
        const formData = new FormData(form);
        let   url      = storeUrl;

        if (id) {                                   // EDIT -> PUT /students/{id}
            url = `${baseUrl}/${id}`;
            formData.append('_method', 'PUT');
        }

        saveBtn.disabled = true;
        saveBtnText.textContent = 'Sending...';

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (res.status === 422) {                // validation errors
                const data = await res.json();
                showErrors(data.errors || {});
                return;
            }

            if (res.status === 419) {                // session / CSRF expired
                generalErr.textContent = 'Session expired. Please refresh the page and try again.';
                generalErr.classList.remove('d-none');
                return;
            }

            if (!res.ok) {
                generalErr.textContent = 'Something went wrong. Please try again.';
                generalErr.classList.remove('d-none');
                return;
            }

            studentModal.hide();
            window.location.reload();                // flash message shown by layout

        } catch (err) {
            generalErr.textContent = 'Network error. Please check your connection.';
            generalErr.classList.remove('d-none');
        } finally {
            saveBtn.disabled = false;
            saveBtnText.textContent = id ? 'Update Student' : 'Save Student';
        }
    });

    // ---------- VIEW modal ----------
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = viewedStudent = studentFromRow(btn);

            document.getElementById('viewName').textContent    = d.name;
            document.getElementById('viewEmail').textContent   = d.email;
            document.getElementById('viewPhone').textContent   = d.phone;
            document.getElementById('viewCourse').textContent  = d.course;
            document.getElementById('viewAddress').textContent = d.address || 'No address provided';
            document.getElementById('viewCreated').textContent = d.created;

            const statusEl = document.getElementById('viewStatus');
            statusEl.textContent = d.status_label;
            statusEl.className   = 'badge rounded-pill ' + d.status_badge;

            const imgWrap     = document.getElementById('viewImageWrap');
            const initialWrap = document.getElementById('viewInitialWrap');

            if (d.image) {
                document.getElementById('viewImage').src = d.image;
                imgWrap.classList.remove('d-none');
                initialWrap.classList.add('d-none');
            } else {
                document.getElementById('viewInitial').textContent = d.name.charAt(0).toUpperCase();
                imgWrap.classList.add('d-none');
                initialWrap.classList.remove('d-none');
            }

            viewModal.show();
        });
    });

    // View modal -> Edit button: close view, then open the shared edit modal
    document.getElementById('viewEditBtn').addEventListener('click', function () {
        const student = viewedStudent;
        viewModalEl.addEventListener('hidden.bs.modal', () => openStudentModal(student), { once: true });
        viewModal.hide();
    });
});
</script>
@endpush