@extends('admin.news.layouts.main')
@section('news-content')
    <a href="{{route('news.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
    <h1 class="font-weight-bold blue-text my-3">Create Article</h1>
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
            <h4 class="blue-text mb-3 fw-600">Primary information</h4>
            <div class="list-group-item p-4 z-depth-1">
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
            <h4 class="blue-text mt-4 mb-3 fw-600">Cover Image</h4>
            <p>A cover image can be displayed for the article, similar to the thumbnail. Please ensure we have the right to use the image, and that it is of an acceptable resolution.</p>
            <div class="list-group-item p-4 z-depth-1">
                <div class="form-group pb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image">
                        <label class="custom-file-label">Choose image</label>
                    </div>

                </div>
                <div class="form-group">
                    <p>Alternatively, pick an already uploaded image.</p>
                    <button class="btn btn-primary mt-4" type="button" data-toggle="collapse" data-target="#collapseExample">
                        Pick uploaded image
                    </button>
                    <div class="collapse" id="collapseExample">
                        <div class="mt-3">
                            <select name="uploadedImage" class="image-picker masonry">
                                <option value=""></option>
                                @foreach($uploadedImgs as $img)
                                <option data-img-src="{{$img->path}}" data-img-class="img-fluid" data-img-alt="{{$img->id}}" value="{{$img->id}}">  {{$img->id}}  </option>
                                @endforeach
                            </select>
                            <script>
                            $("select.image-picker").imagepicker()
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="blue-text mt-4 mb-3 fw-600">Content</h4>
            <div class="list-group-item p-4 z-depth-1">
                <label for="">Use Markdown</label>
                <textarea id="contentMD" name="content" class="w-75"></textarea>
                <script>
                    var simplemde = new EasyMDE({ element: document.getElementById("contentMD"), toolbar: false });
                </script>
            </div>
            <h4 class="blue-text mt-4 mb-3 fw-600">Options</h4>
            <div class="list-group-item p-4 z-depth-1">
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
            <input type="submit" value="Submit Article" class="btn btn-primary mt-4">
        </form>
        </div>
    </div>
</div>
@endsection
