@extends('layouts.app')

@section('title', $student->name . ' - Student Profile')

@section('content')
<div class="container mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <a href="{{ route('students.index') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-indigo-600">
        <i class="fas fa-arrow-left"></i> Back to students
    </a>

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="bg-gradient-to-r from-indigo-700 to-violet-700 px-6 py-8 text-white sm:px-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-5">
                    @if($student->image)
                        <img src="{{ asset('storage/' . $student->image) }}" alt="{{ $student->name }}" class="h-20 w-20 rounded-2xl border-2 border-white/40 object-cover">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/15 text-3xl font-bold">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-indigo-100">Student profile</p>
                        <h1 class="mt-1 text-2xl font-bold sm:text-3xl">{{ $student->name }}</h1>
                        <p class="mt-1 text-sm text-indigo-100">{{ $student->course }}</p>
                    </div>
                </div>
                <span class="inline-flex w-fit items-center rounded-full border border-white/30 bg-white/10 px-3 py-1 text-sm font-semibold">
                    {{ $student->status_label }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Contact details</p>
                <dl class="mt-4 space-y-4">
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="mt-1 break-all font-medium text-gray-900">{{ $student->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Phone</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $student->phone }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Enrollment</p>
                <dl class="mt-4 space-y-4">
                    <div>
                        <dt class="text-xs text-gray-500">Course</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $student->course }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Current status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex rounded-full border px-3 py-1 text-sm font-medium {{ $student->status_badge_class }}">
                                {{ $student->status_label }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Record created</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $student->created_at?->format('d M Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 sm:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Address</p>
                <p class="mt-3 whitespace-pre-line text-gray-800">{{ $student->address ?: 'No address added.' }}</p>
            </div>
        </div>
    </section>
</div>
@endsection
