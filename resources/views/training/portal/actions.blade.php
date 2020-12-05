@extends('training.portal.layouts.main')
@section('page-header-title', 'Actions')
@section('portal-content')
<div class="container py-4">
    <h3 class="blue-text mb-3">Stop your training</h3>
    <p>If you wish to stop your training with Gander Oceanic, or go on an LoA, then contact the Assistant Chief Instructor and CC in your Instructor.</p>
    <p style="font-size: 1.2em;" class="mt-3">
        <a class="font-weight-bold text-body" href="{{route('staff')}}">Find their emails &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
    </p>
</div>
@endsection
