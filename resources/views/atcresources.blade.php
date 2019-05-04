@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <h2>ATC Resources</h2>
    <br/>
    <div class="list-group list-group-flush">
        @foreach ($resources as $resource)
        <div class="list-group-item">
            <div class="row">
                <div class="col"><b>{{$resource->title}}</b></div>
                <div class="col-sm-4">
                    <a href="#" data-toggle="modal" data-target="#detailsModal{{$resource->id}}"><i class="fa fa-info-circle"></i>&nbsp;View Details</a>&nbsp;&nbsp;
                    <a href="{{$resource->url}}" target="_blank"><i class="fa fa-download"></i>&nbsp;Download</a>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailsModal{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{$resource->title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small>Description</small><br/>
                        {!!html_entity_decode($resource->description)!!}
                    </div>
                    <div class="modal-footer">
                        <a href="#" role="button" class="btn btn-danger">Delete File</a>
                        <a href="{{$resource->url}}" role="button" class="btn btn-success">Download File</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <br/>
    @if (Auth::check() && Auth::user()->permissions >= 3)    
    <form method="POST">
        @csrf
        <h5>Add resource</h5>
        <div class="form-group">
            <label>Title</label>
            <input required class="form-control" type="text" placeholder="Sector files 1903" name="title">
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" id="descriptionField" name="description">
            <script>
                tinymce.init({
                    selector: '#descriptionField',
                    plugins: 'link media table',
                    menubar: 'edit insert format'
                });
            </script>
        </div>
        <div class="form-group">
            <label>File</label>
            <input type="file" class="form-control-file" name="file">
        </div>
        <div class="m-1" style="text-align: center">
            OR
        </div>
        <div class="form-group">
            <label>Link (URL)</label>
            <input type="url" class="form-control" name="link">
        </div>
        <br/>
        <input type="submit" class="btn btn-sm btn-block btn-success">
    @endif
</div>
@stop