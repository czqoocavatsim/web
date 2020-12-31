@extends('admin.settings.layouts.main')

@section('settings-content')
    <div class="container py-4">
        <h1 class="blue-text font-weight-bold mt-2">Rotation Images</h1>
        <h5 class="font-weight-bold blue-text">Current Images</h5>
        <div class="row">
            @foreach ($images as $image)
                <div class="col-md-3">
                    <div class="list-group-item rounded z-depth-1 shadow-none">
                            <img src="{{$image->path}}" alt="" class="img-fluid">
                            <div class="mt-2 d-flex flex-row justify-content-between align-items-center">
                                <div>
                                    <a href="{{$image->path}}">Path</a>
                                </div>
                                <div>
                                    <a href="{{route('settings.rotationimages.deleteimg', $image->id)}}" class="btn btn-sm btn-danger">Delete</a>
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
        <h5 class="mt-4 font-weight-bold blue-text">Upload Image</h5>
        <form method="post" action="{{route('settings.rotationimages.uploadimg')}}" enctype="multipart/form-data" class="" id="">
            @csrf
            <div class="input-group pb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file">
                    <label class="custom-file-label">Choose image (.png or .jpg)</label>
                </div>
            </div>
            <button class="btn btn-success btn-sm">Upload</button>
        </form>
    </div>
@stop
