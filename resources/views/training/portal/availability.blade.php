@extends('training.portal.layouts.main')
@section('page-header-title', 'Your availability')
@section('portal-content')
<div class="container py-4">
    <div class="list-group z-depth-1">
        @foreach($availability as $a)
            <div class="list-group-item p-4">
                <h5>Submitted at {{$a->created_at->toDayDateTimeString()}}</h5>
                <hr>
                {{$a->submissionHtml()}}
            </div>
        @endforeach
    </div>
    <p class="my-4">If your availability has changed, let your Instructor know.</p>
</div>
@endsection
