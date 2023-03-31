@extends('admin.news.layouts.main')
@section('news-content')
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
                                    <option value="{{$article->user_id}}">{{$article->user->fullName('FLC')}}</option>
                                </select>
                                <div class="ml-3">
                                    @if ($article->show_author == false)
                                    <input type="checkbox" disabled name="showAuthor" class="" id="defaultUnchecked">
                                    @else
                                    <input type="checkbox" disabled name="showAuthor" class="" id="defaultUnchecked" checked>
                                    @endif
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
                                @if ($article->visible == true)
                                <input type="checkbox" checked name="articleVisible" id="articleVisible" disabled>
                                @else
                                <input type="checkbox" name="articleVisible" id="articleVisible" disabled>
                                @endif
                                <label class="" for="">Publicly visible (published)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex flex-col">
                            Email level: {{ $email_level }}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@can('edit article')
<!--edit article modal-->
<div class="modal fade" id="editarticle" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit {{$article->title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('news.article.update.post', $article->slug)}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($errors->editArticleErrors->any())
                    <div class="alert alert-danger">
                        <h4>An error occurred whilst trying to edit the event</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->editArticleErrors->all() as $error)
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
                                    <input type="text" name="title" id="" class="form-control" value={{$article->title}}>
                                </div>
                                <div class="form-group">
                                    <label for="">Author</label>
                                    <div class="d-flex flex-row justify-content-between">
                                        <select class="custom-select" name="author">
                                            <option value="{{$article->user_id}}" selected>{{$article->user->fullName('FLC')}}</option>
                                            @foreach ($staff as $s)
                                                <option value="{{$s->user->id}}">{{$s->user->fullName('FLC')}} ({{$s->position}})</option>
                                            @endforeach
                                        </select>
                                        <div class="ml-3">
                                            @if ($article->show_author == false)
                                            <input type="checkbox" disabled name="showAuthor" class="" id="defaultUnchecked">
                                            @else
                                            <input type="checkbox" disabled name="showAuthor" class="" id="defaultUnchecked" checked>
                                            @endif
                                            <label class="" for="defaultUnchecked">Show author publicly</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Summary</label>
                                    <input type="text" name="summary" id="" class="form-control" value={{$article->summary}}>
                                </div>
                            </div>
                        </li>
                        <li class="active">
                            <a href="#!">
                                <span class="circle">2</span>
                                <span class="label">Description</span>
                            </a>
                            <div class="step-content w-75 pt-0">
                                <div class="form-group">
                                    <label for="">Use Markdown</label>
                                    <textarea id="contentMD" name="content" class="w-75">{{$article->content}}</textarea>
                                    <script>
                                        var simplemde = new EasyMDE({ element: document.getElementById("contentMD"), toolbar: false });
                                    </script>
                                </div>
                            </div>
                        </li>
                        <li class="active">
                            <a href="#!">
                                <span class="circle">3</span>
                                <span class="label">Image</span>
                            </a>
                            <div class="step-content w-75 pt-0">
                                @if ($article->image)
                                <img src="{{$article->image}}" alt="" class="img-fluid w-50 img-thumbnail">
                                @else
                                No image.
                                @endif
                                <p>A cover image can be displayed for the article, similar to the thumbnail. Please ensure we have the right to use the image, and that it is of an acceptable resolution.</p>
                                <div class="form-group pb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image">
                                        <label class="custom-file-label">Choose image</label>
                                    </div>
                                </div>
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
                                        @if ($article->visible == true)
                                        <input type="checkbox" checked name="articleVisible" id="articleVisible">
                                        @else
                                        <input type="checkbox" name="articleVisible" id="articleVisible">
                                        @endif
                                        <label class="" for="">Publicly visible (published)</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if($errors->editArticleErrors->any())
<script>
    $("#editarticle").modal('show');
</script>
@endif
@endcan
@endsection
