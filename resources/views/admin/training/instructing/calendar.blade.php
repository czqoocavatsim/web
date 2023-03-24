@extends('admin.training.layouts.main')
@section('title', 'Board - Instructing - ')
@section('training-content')
@push('styles')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endpush

<main class="flex-grow">
    <h1 class="text-xl">Calendar</h1>
    <p class="mb-2 ">Upcoming training and OTS sessions</p>
    <div>
        <livewire:training.instructing.calendar before-calendar-view="livewire/training/instructing/before-calendar" :drag-and-drop-enabled="false"/>
    </div>
</main>

@endsection


