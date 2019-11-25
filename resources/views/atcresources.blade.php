@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('title', 'ATC Resources - ')

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
                    <a href="#" data-toggle="modal" data-target="#detailsModal{{$resource->id}}"><i class="fa fa-info-circle"></i>&nbsp Details</a>&nbsp;&nbsp;
                    <a href="{{$resource->url}}" target="_blank"><i class="fa fa-eye"></i>&nbsp;View Resource</a>
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
                        @if (Auth::check() && Auth::user()->permissions >= 3)
                        <a href="{{route('atcresources.delete', $resource->id)}}" role="button" class="btn btn-danger">Delete</a>
                        @endif
                        <a href="{{$resource->url}}" role="button" class="btn btn-success">View</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <br/>
    @if (Auth::check() && Auth::user()->permissions >= 3)
    <form method="POST" action="{{route('atcresources.upload')}}">
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
            <label>URL (Google Drive or Dropbox preferred)</label>
            <input type="url" class="form-control" name="url">
        </div>
        <br/>
        <input value="Submit" type="submit" class="btn btn-sm btn-block btn-success">
    </form>
    @endif
</div>
@stop
