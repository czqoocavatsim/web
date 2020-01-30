@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('news.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
    <h1 class="font-weight-bold blue-text">Create Article</h1>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <ul class="stepper mt-0 p-0 stepper-vertical">
                <li class="active">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Primary information</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <label for="">Article title</label>
                            <input type="text" name="" id="" class="form-control" placeholder="New sector files released, etc.">
                        </div>
                        <div class="form-group">
                            <label for="">Author</label>
                            <div class="d-flex flex-row justify-content-between">
                                <select class="custom-select">
                                    <option value="{{Auth::id()}}" selected>You</option>
                                    @foreach ($staff as $s)
                                        <option value="{{$s->user->id}}">{{$s->user->fullName('FLC')}} ({{$s->position}})</option>
                                    @endforeach
                                </select>
                                <div class="ml-3 custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="defaultUnchecked">
                                    <label class="custom-control-label" for="defaultUnchecked">Show author publicly</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Summary</label>
                            <input type="text" name="" id="" class="form-control" placeholder="Short description of the article">
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Image</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <p>An image can be displayed for the article, similar to the thumbnail. Please ensure we have the right to use the image, and that it is of an acceptable resolution.</p>
                        <div class="input-group pb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file">
                                <label class="custom-file-label">Choose image</label>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">3</span>
                        <span class="label">Content</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <label for="">Use Markdown</label>
                        <textarea name="" id="contentMD" class="w-75"></textarea>
                        <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
                        </script>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">4</span>
                        <span class="label">Options</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mr-2">
                                <input type="checkbox" class="custom-control-input" name="articleVisible" id="articleVisible">
                                <label class="custom-control-label" for="">Publicly visible (published)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Email Options</label>
                            <div class="d-flex flex-col">
                            <div class="custom-control custom-radio mr-2">
                                <input type="radio" class="custom-control-input" id="defaultUnchecked" name="emailOption">
                                <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
                            </div>
                            <div class="custom-control custom-radio mr-2">
                                <input type="radio" class="custom-control-input" id="defaultUnchecked" name="emailOption">
                                <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
                            </div>
                            <div class="custom-control custom-radio mr-2">
                                <input type="radio" class="custom-control-input" id="defaultUnchecked" name="emailOption">
                                <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="defaultUnchecked" name="emailOption">
                                <label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
                            </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
