@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('news.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
    <h1 class="font-weight-bold blue-text">Create Article</h1>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('news.articles.create.post')}}" enctype="multipart/form-data">
            @csrf
            @if($errors->createArticleErrors->any())
            <div class="alert alert-danger">
                <h4>There were errors submitting the article</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createArticleErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <ul class="stepper mt-0 p-0 stepper-vertical">
                <li class="active">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Primary information</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <label for="">Article title</label>
                            <input type="text" name="title" id="" class="form-control" placeholder="New sector files released, etc.">
                        </div>
                        <div class="form-group">
                            <label for="">Author</label>
                            <div class="d-flex flex-row justify-content-between">
                                <select class="custom-select" name="author">
                                    <option value="{{Auth::id()}}" selected>You</option>
                                    @foreach ($staff as $s)
                                        <option value="{{$s->user->id}}">{{$s->user->fullName('FLC')}} ({{$s->position}})</option>
                                    @endforeach
                                </select>
                                <div class="ml-3">
                                    <input type="checkbox" name="showAuthor" class="" id="defaultUnchecked">
                                    <label class="" for="defaultUnchecked">Show author publicly</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Summary</label>
                            <input type="text" name="summary" id="" class="form-control" placeholder="Short description of the article">
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
                                <input type="file" class="custom-file-input" name="image">
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
                        <textarea id="contentMD" name="content" class="w-75"></textarea>
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
                            <div class="mr-2">
                                <input type="checkbox" checked="true"  class="" name="articleVisible" id="articleVisible">
                                <label class="" for="">Publicly visible (published)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Email Options <span onclick="showExplanationEmail()">(click for explanation)</span></label>
                            <script>
                                function showExplanationEmail() {
                                    alert("No email: no email is sent\nEmail controllers: all rostered controllers are emailed.\nEmail all: all subscribed users are emailed\nEmail all, important: ALL users are emailed. Use for important news only in line with the privacy policy.\n\nOnly use emails once for one item of news, unless a reminder is required.");
                                }
                            </script>
                            <div class="d-flex flex-col">
                            <div class="mr-2">
                                <input type="radio" value="no" checked="true" class="" id="defaultUnchecked" name="emailOption">
                                <label class="" for="defaultUnchecked">No email</label>
                            </div>
                            <div class="mr-2">
                                <input type="radio" value="controllers" class="" id="defaultUnchecked" name="emailOption">
                                <label class="" for="defaultUnchecked">Email controllers</label>
                            </div>
                            <div class="mr-2">
                                <input type="radio" value="all" class="" id="defaultUnchecked" name="emailOption">
                                <label class="" for="defaultUnchecked">Email all</label>
                            </div>
                            <div class="">
                                <input type="radio" value="allimportant" class="" id="defaultUnchecked" name="emailOption">
                                <label class="" for="defaultUnchecked">Email all, important</label>
                            </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <input type="submit" value="Submit Article" class="btn btn-primary">
        </form>
        </div>
    </div>
</div>
@endsection
