@extends('layouts.master')
@section('content')
<div class="container py-4">
    <h2>Create Article</h2>
    <form action="">
    @csrf
    <div class="md-form">
        <input type="text" name="title" id="title" class="form-control">
        <label for="title">Article title</label>
    </div>
    <div class="row">
        <div class="col-md-8">
            <label for="">Author</label>
            <select class="custom-select">
                <option value="{{Auth::id()}}" selected>You</option>
                @foreach ($staff as $s)
                <option value="{{$s->user->id}}">{{$s->user->fullName('FLC')}} ({{$s->position}})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="defaultUnchecked">
                <label class="custom-control-label" for="defaultUnchecked">Show author publicly</label>
            </div>
        </div>
    </div>
    <div class="md-form">
        <textarea name="" id="" rows="2" class="md-textarea form-control"></textarea>
        <label for="">Summary</label>
        <small>If this isn't filled, the first few sentences will be used.</small>
    </div>
    <div class="form mb-3">
        <label for="">Image</label>
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="file">
                <label class="custom-file-label">Choose image file</label>
            </div>
        </div>
        <small>If this isn't selected, a solid colour will be used.</small>
    </div>
    <div class="form mb-3">
        <label for="">Content</label>
        <textarea name="" id="contentMD" cols="30" rows="10"></textarea>
        <script>
            var simplemde = new SimpleMDE({ element: document.getElementById("contentMD") });
        </script>
    </div>
    <div class="form-mb-3">
        <label for="">Options</label>
        <div class="d-flex flex row mx-1">
            <div class="custom-control custom-checkbox mr-2">
                <input type="checkbox" class="custom-control-input" id="articleVisible">
                <label class="custom-control-label" for="">Publicly visible (published)</label>
            </div>
        </div>
    </div>
    <div class="form-mb-3">
        <label for="">Email Options</label>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="defaultUnchecked" name="defaultExampleRadios">
            <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="defaultUnchecked" name="defaultExampleRadios">
            <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="defaultUnchecked" name="defaultExampleRadios">
            <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="defaultUnchecked" name="defaultExampleRadios">
            <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
        </div>
    </div>
    </form>
</div>
@endsection
