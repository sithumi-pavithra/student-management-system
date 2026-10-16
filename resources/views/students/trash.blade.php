@extends('layouts.app')

@section('title', 'Trash - Deleted Students')

@section('content')
    <div class="container mx-auto px-6 py-8">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🗑️ Trash</h1>
                <p class="text-gray-500 mt-1">Deleted students can be restored from here</p>
            </div>
            <a href="{{ route('students.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
                <i class="fas fa-arrow-left"></i> Back to Students
            </a>
        </div>

        {{-- Trash Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-2">
                    <i class="fas fa-trash text-gray-500"></i>
                    <h2 class="text-lg font-semibold text-gray-800">Deleted Students</h2>
                    <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ $students->count() }}</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deleted At</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $student)
                        <tr class="table-row">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                    {{ $student->course }}
                                </span>
                            </td>
                            
                            {{-- Status (Combined: Status + Deleted) --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $student->overall_status_badge_class }} rounded-full text-xs font-medium border">
                                    {{ $student->overall_status_label }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $student->deleted_at->format('Y-m-d H:i') }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    {{-- Restore --}}
                                    <form method="POST" action="{{ route('students.restore', $student->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-medium flex items-center gap-1 transition">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                    </form>
                                    
                                    {{-- Force Delete --}}
                                    <form method="POST" 
                                          action="{{ route('students.forceDelete', $student->id) }}" 
                                          class="inline"
                                          onsubmit="return confirm('⚠️ Permanently delete this student? This cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-medium flex items-center gap-1 transition">
                                            <i class="fas fa-trash-alt"></i> Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="text-6xl mb-4">🗑️</div>
                                <p class="text-gray-500 font-medium">No deleted students</p>
                                <p class="text-sm text-gray-400 mt-1">Deleted students will appear here</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection