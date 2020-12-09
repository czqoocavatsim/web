@extends('layouts.primary', ['adminNavBar'=>true])
@section('content')
<div class="container py-4">
    <a href="{{route('news.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
    <h1 class="font-weight-bold blue-text">{{$article->title}}</h1>
    <h5>Published {{$article->published_pretty()}}</h5>
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
                            <input type="text" name="title" value="{{$article->title}}" id="" class="form-control disabled" placeholder="New sector files released, etc.">
                        </div>
                        <div class="form-group">
                            <label for="">Author</label>
                            <div class="d-flex flex-row justify-content-between">
                                <select class="custom-select disabled" value="{{$article->user_id}}" name="author">
                                    @foreach ($staff as $s)
                                        <option value="{{$s->user->id}}">{{$s->user->fullName('FLC')}} ({{$s->position}})</option>
                                    @endforeach
                                    <option value="{{$article->user_id}}">{{$article->user->fullName('FLC')}}</option>
                                </select>
                                <div class="ml-3">
                                    <input type="checkbox" disabled name="showAuthor" class="" id="defaultUnchecked">
                                    <label class="" for="defaultUnchecked">Show author publicly</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Summary</label>
                            <input type="text" name="summary" value="{{$article->summary}}" id="" class="form-control disabled" placeholder="Short description of the article">
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Image</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        @if ($article->image)
                        <img src="{{$article->image}}" alt="" class="img-fluid w-50 img-thumbnail">
                        @else
                        No image.
                        @endif
                        <p class="mt-4">An image can be displayed for the article, similar to the thumbnail. Please ensure we have the right to use the image, and that it is of an acceptable resolution.</p>
                        <div class="input-group pb-3">
                            <div class="custom-file">
                                <input type="file" disabled class="custom-file-input" name="image">
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
                        <blockquote class="blockquote">
                            {{$article->html()}}
                        </blockquote>
                        {{-- <label for="">Use Markdown</label>
                        <textarea id="contentMD" name="content" class="w-75"></textarea>
                        <script>
                            var simplemde = new EasyMDE({ element: document.getElementById("contentMD"), toolbar: false, disabled: true });
                        </script> --}}
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">4</span>
                        <span class="label">Options</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <div class="mr-2">
                                <input type="checkbox" checked="true"  class="" name="articleVisible" id="articleVisible">
                                <label class="" for="">Publicly visible (published)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex flex-col">
                            Email level: asd
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
