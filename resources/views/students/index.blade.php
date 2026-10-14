@extends('layouts.app')

@section('title', 'Students List - Student Management')

@section('content')
    <div class="container mx-auto px-6 py-8">

        {{-- HEADER  --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🎓 Students List</h1>
                <p class="text-gray-500 mt-1">Manage all registered students</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('students.trash') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
                    <i class="fas fa-trash"></i> Trash
                    @if($stats['trashed'] > 0)
                        <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">
                            {{ $stats['trashed'] }}
                        </span>
                    @endif
                </a>
                <button type="button" onclick="openAddModal()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition shadow-lg shadow-indigo-200">
                    <i class="fas fa-plus"></i> Add New Student
                </button>
            </div>
        </div>

        {{-- ═══════════════ STATS CARDS ═══════════════ --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Total</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-indigo-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Active</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Graduated</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['graduated'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase">Trashed</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['trashed'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-trash text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- STUDENT CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <section class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <div class="mb-5">
                    <h2 class="text-base font-semibold text-gray-800">Students by course</h2>
                    <p class="mt-1 text-sm text-gray-500">Current student distribution across courses</p>
                </div>
                <div class="relative h-72">
                    <canvas id="courseStudentsChart" aria-label="Bar chart showing students by course"></canvas>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <div class="mb-5">
                    <h2 class="text-base font-semibold text-gray-800">Students by status</h2>
                    <p class="mt-1 text-sm text-gray-500">Overview of active, inactive, graduated, and suspended students</p>
                </div>
                <div class="relative h-72">
                    <canvas id="studentStatusChart" aria-label="Doughnut chart showing students by status"></canvas>
                </div>
            </section>
        </div>
        {{-- SEARCH + FILTER + TABLE --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">All Students</h2>
                    <p class="text-xs text-gray-500">{{ $students->total() }} registered students</p>
                </div>

                <form method="GET" action="{{ route('students.index') }}" class="flex flex-col sm:flex-row gap-2">
                    <input type="search" name="search" value="{{ request('search') }}"
                           placeholder="Search name, email, phone"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none">

                    <select name="course"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none">
                        <option value="">All courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course }}" @selected(request('course') === $course)>{{ $course }}</option>
                        @endforeach
                    </select>

                    <select name="status"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none">
                        <option value="">All statuses</option>
                        <option value="active"    @selected(request('status') === 'active')>🟢 Active</option>
                        <option value="inactive"  @selected(request('status') === 'inactive')>🟡 Inactive</option>
                        <option value="graduated" @selected(request('status') === 'graduated')>🔵 Graduated</option>
                        <option value="suspended" @selected(request('status') === 'suspended')>🔴 Suspended</option>
                    </select>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Apply
                    </button>

                    @if(request()->filled('search') || request()->filled('course') || request()->filled('status'))
                        <a href="{{ route('students.index') }}" class="px-3 py-2 text-sm text-gray-600 hover:text-indigo-600">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $students->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($student->image)
                                        <img src="{{ asset('storage/' . $student->image) }}"
                                             class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-100">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('students.show', $student->id) }}" class="text-sm font-semibold text-gray-800 hover:text-indigo-600">{{ $student->name }}</a>
                                        <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $student->phone }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                    {{ $student->course }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $student->status_badge_class }} rounded-full text-xs font-medium border">
                                    {{ $student->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button type="button"
                                            class="edit-btn w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition"
                                            data-id="{{ $student->id }}"
                                            data-name="{{ $student->name }}"
                                            data-email="{{ $student->email }}"
                                            data-phone="{{ $student->phone }}"
                                            data-course="{{ $student->course }}"
                                            data-status="{{ $student->status }}"
                                            data-address="{{ $student->address }}"
                                            data-image="{{ $student->image ? asset('storage/' . $student->image) : '' }}"
                                            title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>

                                    <form method="POST"
                                          action="{{ route('students.destroy', $student->id) }}"
                                          class="inline"
                                          onsubmit="return confirm('Move to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition"
                                                title="Delete">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="text-6xl mb-4">📭</div>
                                <p class="text-gray-500 font-medium">No students found</p>
                                <p class="text-sm text-gray-400 mt-1">Click "Add New Student" to get started</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $students->links() }}
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ADD STUDENT MODAL
         ═══════════════════════════════════════════════════════════ --}}
    <div id="addStudentModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         onclick="closeAddModal(event)">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">

            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-6 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Add New Student</h2>
                        <p class="text-xs text-indigo-200">Fill in the details below</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal({ force: true })"
                        class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="p-8">
                @csrf

                @if($errors->any() && !old('_method'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <span class="font-semibold">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc pl-8 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- NAME --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-indigo-500 mr-1"></i> Full Name *
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="Enter student name">
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-indigo-500 mr-1"></i> Email *
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="student@example.com">
                    </div>

                    {{-- PHONE ⭐ FIXED ⭐ --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-indigo-500 mr-1"></i> Phone *
                        </label>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required
                               pattern="[0-9]{10}"
                               maxlength="10"
                               title="Please enter exactly 10 digits"
                               inputmode="numeric"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="0712345678">
                    </div>

                    {{-- COURSE --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book text-indigo-500 mr-1"></i> Course *
                        </label>
                        <input type="text" name="course" value="{{ old('course') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition"
                               placeholder="e.g., HNDIT, HNDA">
                    </div>

                    {{-- STATUS --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag text-indigo-500 mr-1"></i> Status *
                        </label>
                        <select name="status" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition">
                            <option value="active"    @selected(old('status', 'active') === 'active')>🟢 Active</option>
                            <option value="inactive"  @selected(old('status') === 'inactive')>🟡 Inactive</option>
                            <option value="graduated" @selected(old('status') === 'graduated')>🔵 Graduated</option>
                            <option value="suspended" @selected(old('status') === 'suspended')>🔴 Suspended</option>
                        </select>
                    </div>

                    {{-- IMAGE --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-indigo-500 mr-1"></i> Profile Image
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition">
                            <input type="file" name="image" id="create_imageInput" accept="image/*"
                                   class="hidden" onchange="showCreateFileName()">
                            <label for="create_imageInput" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Click to upload</p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG up to 2MB</p>
                            </label>
                            <p id="create_fileName" class="text-sm text-indigo-600 font-medium mt-2"></p>
                        </div>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-indigo-500 mr-1"></i> Address
                        </label>
                        <textarea name="address" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition"
                                  placeholder="Enter address">{{ old('address') }}</textarea>
                    </div>

                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-medium flex items-center gap-2 transition shadow-lg shadow-indigo-200">
                        <i class="fas fa-save"></i> Save Student
                    </button>
                    <button type="button" onclick="closeAddModal({ force: true })"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-medium transition">
                        Cancel
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         EDIT STUDENT MODAL
         ═══════════════════════════════════════════════════════════ --}}
    <div id="editStudentModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         onclick="closeEditModal(event)">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto"
             onclick="event.stopPropagation()">

            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-6 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-edit text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Edit Student</h2>
                        <p class="text-xs text-blue-200">Update student details</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal({ force: true })"
                        class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form method="POST" id="editStudentForm" action="" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div id="editCurrentImage" class="hidden mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image text-blue-500 mr-1"></i> Current Image
                    </label>
                    <div class="flex items-center gap-4">
                        <img id="editCurrentImagePreview" src=""
                             class="w-20 h-20 rounded-full object-cover ring-4 ring-blue-100">
                        <p class="text-sm text-gray-500">Current profile image</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- NAME --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-500 mr-1"></i> Full Name *
                        </label>
                        <input type="text" name="name" id="edit_name" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-blue-500 mr-1"></i> Email *
                        </label>
                        <input type="email" name="email" id="edit_email" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                    </div>

                    {{-- PHONE FIXED — only ONE input now  --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone text-blue-500 mr-1"></i> Phone *
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="edit_phone" 
                               required
                               pattern="[0-9]{10}"
                               maxlength="10"
                               title="Please enter exactly 10 digits"
                               inputmode="numeric"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                    </div>

                    {{-- COURSE --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book text-blue-500 mr-1"></i> Course *
                        </label>
                        <input type="text" name="course" id="edit_course" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                    </div>

                    {{-- STATUS --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag text-blue-500 mr-1"></i> Status *
                        </label>
                        <select name="status" id="edit_status" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                            <option value="active">🟢 Active</option>
                            <option value="inactive">🟡 Inactive</option>
                            <option value="graduated">🔵 Graduated</option>
                            <option value="suspended">🔴 Suspended</option>
                        </select>
                    </div>

                    {{-- IMAGE --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-blue-500 mr-1"></i> New Profile Image
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                            <input type="file" name="image" id="edit_imageInput" accept="image/*"
                                   class="hidden" onchange="showEditFileName()">
                            <label for="edit_imageInput" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Click to upload new image</p>
                                <p class="text-xs text-gray-400 mt-1">Leave empty to keep current</p>
                            </label>
                            <p id="edit_fileName" class="text-sm text-blue-600 font-medium mt-2"></p>
                        </div>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> Address
                        </label>
                        <textarea name="address" id="edit_address" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition"></textarea>
                    </div>

                </div>

                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium flex items-center gap-2 transition shadow-lg shadow-blue-200">
                        <i class="fas fa-save"></i> Update Student
                    </button>
                    <button type="button" onclick="closeEditModal({ force: true })"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-medium transition">
                        Cancel
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courseCanvas = document.getElementById('courseStudentsChart');
        const statusCanvas = document.getElementById('studentStatusChart');

        if (courseCanvas) {
            new Chart(courseCanvas, {
                type: 'bar',
                data: {
                    labels: @json($courseStats->pluck('course')),
                    datasets: [{
                        label: 'Students',
                        data: @json($courseStats->pluck('total')),
                        backgroundColor: '#5865d8',
                        borderRadius: 7,
                        maxBarThickness: 44
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#667085' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: '#667085' },
                            grid: { color: '#edf0f5' }
                        }
                    }
                }
            });
        }

        if (statusCanvas) {
            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive', 'Graduated', 'Suspended'],
                    datasets: [{
                        data: [
                            @json($stats['active']),
                            @json($stats['inactive'] ?? 0),
                            @json($stats['graduated']),
                            @json($stats['suspended'] ?? 0)
                        ],
                        backgroundColor: ['#16a36a', '#e8a317', '#3976df', '#dc4b4b'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 18, color: '#526078' }
                        }
                    }
                }
            });
        }
    });
</script>
<script>
    //  ADD MODAL 
    function openAddModal() {
        const modal = document.getElementById('addStudentModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const firstInput = modal.querySelector('input[name="name"]');
                if (firstInput) firstInput.focus();
            }, 100);
        }
    }

    function closeAddModal(event) {
        if (event && event.force) {
            document.getElementById('addStudentModal').classList.add('hidden');
            document.body.style.overflow = '';
            return;
        }
        if (event && event.target.id !== 'addStudentModal') return;
        document.getElementById('addStudentModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function showCreateFileName() {
        const input = document.getElementById('create_imageInput');
        const fileName = document.getElementById('create_fileName');
        if (input && input.files && input.files[0]) {
            fileName.textContent = '✅ ' + input.files[0].name;
        }
    }

    // ═══════════════ EDIT MODAL ═══════════════
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const student = {
                    id:      this.dataset.id,
                    name:    this.dataset.name,
                    email:   this.dataset.email,
                    phone:   this.dataset.phone,
                    course:  this.dataset.course,
                    status:  this.dataset.status,
                    address: this.dataset.address,
                    image:   this.dataset.image,
                };
                openEditModal(student);
            });
        });
    });

    function openEditModal(student) {
        const modal = document.getElementById('editStudentModal');
        if (!modal) return;

        document.getElementById('edit_name').value = student.name;
        document.getElementById('edit_email').value = student.email;
        document.getElementById('edit_phone').value = student.phone;
        document.getElementById('edit_course').value = student.course;
        document.getElementById('edit_status').value = student.status;
        document.getElementById('edit_address').value = student.address || '';
        document.getElementById('editStudentForm').action = `/students/${student.id}`;

        const imageSection = document.getElementById('editCurrentImage');
        const imagePreview = document.getElementById('editCurrentImagePreview');
        if (student.image && student.image.trim() !== '') {
            imagePreview.src = student.image;
            imageSection.classList.remove('hidden');
        } else {
            imageSection.classList.add('hidden');
        }

        const fileInput = document.getElementById('edit_imageInput');
        if (fileInput) fileInput.value = '';
        document.getElementById('edit_fileName').textContent = '';

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        setTimeout(() => document.getElementById('edit_name').focus(), 100);
    }

    function closeEditModal(event) {
        if (event && event.force) {
            document.getElementById('editStudentModal').classList.add('hidden');
            document.body.style.overflow = '';
            return;
        }
        if (event && event.target.id !== 'editStudentModal') return;
        document.getElementById('editStudentModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function showEditFileName() {
        const input = document.getElementById('edit_imageInput');
        const fileName = document.getElementById('edit_fileName');
        if (input && input.files && input.files[0]) {
            fileName.textContent = '✅ ' + input.files[0].name;
        }
    }

    // ═══════════════ ESC KEY ═══════════════
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const addModal = document.getElementById('addStudentModal');
            const editModal = document.getElementById('editStudentModal');
            if (addModal && !addModal.classList.contains('hidden')) {
                closeAddModal({ force: true });
            }
            if (editModal && !editModal.classList.contains('hidden')) {
                closeEditModal({ force: true });
            }
        }
    });

    // Auto-open Add modal if validation failed
    @if($errors->any() && !old('_method'))
        document.addEventListener('DOMContentLoaded', function() {
            openAddModal();
        });
    @endif
</script>
@endpush