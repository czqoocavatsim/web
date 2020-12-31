@extends('layouts.primary', ['solidNavBar' => false])

@section('title', 'New Feedback - ')

@section('content')
    <div class="card card-image blue rounded-0">
        <div class="text-white text-left pb-2 pt-5 px-4">
            <div class="container">
                <div class="pt-5 pb-3">
                    <a href="{{route('my.index')}}" class="white-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
                </div>
                <div class="pb-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">New feedback</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <p class="lead fw-600">You are submitting {{$type->name}}.</p>
        @if ($errors->newFeedbackErrors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 list-unstyled">
                @foreach ($errors->newFeedbackErrors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="" method="POST">
            @csrf
            @foreach($type->fields as $field)
                <div class="form-group">
                    <h4 class="fw-700 blue-text mt-4">{{$field->name}}</h4>
                    <div class="form-outline">
                        <input type="text" name="{{$field->id}}" placeholder="" @if ($field->required) required @endif class="form-control border rounded px-2 mt-3">
                    </div>
                </div>
            @endforeach
            <div class="form-group">
                <h4 class="fw-700 blue-text mt-4">Your feedback</h4>
                <textarea id="contentMD" name="submission_content" style="display:none; height:" ></textarea>
                <script>
                    var simplemde = new EasyMDE({ maxHeight: '200px', autofocus: true, autoRefresh: true, element: document.getElementById("contentMD")});
                </script>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox" style="text-align:left;">
                    <input name="publishPermission" type="checkbox" class="custom-control-input" id="defaultUnchecked">
                    <label class="custom-control-label fw-600" for="defaultUnchecked">Allow Gander Oceanic to publish your feedback</label>
                </div>
            </div>
            <button class="mt-3 btn btn-success"><i class="fas fa-check mr-3"></i>Submit Feedback</button>
        </form>
    </div>
@endsection
