@extends('admin.publications.layouts.main')
@section('publications-content')
    <h1 class="font-weight-bold blue-text pb-2">ATC Resources</h1>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#createAtcResourceModal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Create ATC Resource</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="list-group">
                @if(count($atcResources) == 0) No ATC Resources found @endif
                @foreach($atcResources as $resource)
                    <div class="list-group-item">
                        <div class="d-flex w-100 flex-row">
                            <h4 class="mr-auto">{{$resource->title}}</h4>
                            <div>
                                <a href="" data-toggle="modal" data-target="#editAtcResource{{$resource->id}}Modal" class="blue-text"><i class="fas fa-pen"></i>   Edit</button></a>
                                &nbsp;
                                <a href="" class="red-text" data-toggle="modal" data-target="#deleteAtcResource{{$resource->id}}Modal"><i class="fa fa-times"></i>   Delete</button></a>
                            </div>
                        </div>
                        <p>{{$resource->description}}</p>
                        <div class="d-flex flex-row">
                            <div>
                                <h6>Created on</h6>
                                <p class="mb-0">{{$resource->created_at->toFormattedDateString()}}</p>
                            </div>
                            <div class="ml-3">
                                <h6>Created by</h6>
                                <p class="mb-0">{{$resource->user->fullName('FLC')}}</p>
                            </div>
                            <div class="ml-3">
                                <h6>Visibility</h6>
                                <p class="mb-0">{{!$resource->atc_only ? 'Public' : 'ATC Only'}}</p>
                            </div>
                            <div class="ml-3">
                                <h6>URL</h6>
                                <p class="mb-0"><a href="{{$resource->url}}">Link</a></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!--Start create modal-->
<div class="modal fade" id="createAtcResourceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create ATC Resource</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('publications.atc-resources.create.post')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->createAtcResourceErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->createAtcResourceErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" value="{{old('title')}}" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" value="{{old('description')}}" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Visibility</label>
                        <select name="visibility" id="" value="{{old('visibility')}}" selected="{{old('visibility')}}" class="form-control">
                            <option value="0" {{old('visibility') == 0 ? 'selected' : ''}}>Public</option>
                            <option value="1" {{old('visibility') == 1 ? 'selected' : ''}}>ATC Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">PDF URL</label>
                        <input type="text" value="{{old('url')}}" name="url" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Create">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End create modal-->

@foreach($atcResources as $r)

<!--Delete Atc Resource {{$r->id}} modal-->
<div class="modal fade" id="deleteAtcResource{{$r->id}}Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a href="{{route('publications.atc-resources.delete', $r->id)}}" role="button" class="btn btn-danger">Delete</a>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End delete Atc Resource {{$r->id}} modal-->


<!--Start edit {{$r->id}} modal-->
<div class="modal fade" id="editAtcResource{{$r->id}}Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit ATC Resource "{{$r->title}}"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('publications.atc-resources.edit.post', $r->id)}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->editAtcResourceErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->editAtcResourceErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" value="{{old('title', $r->title)}}" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" value="{{old('description', $r->description)}}" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Visibility</label>
                        <select name="visibility" id="" value="{{old('visibility', $r->atc_only)}}" class="form-control">
                            <option value="0" {{old('visibility', $r->atc_only) == 0 ? 'selected' : ''}}>Public</option>
                            <option value="1" {{old('visibility', $r->atc_only) == 1 ? 'selected' : ''}}>ATC Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">PDF URL</label>
                        <input type="text" value="{{old('url', $r->url)}}" name="url" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Edit">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End edit {{$r->id}} modal-->

@endforeach

<script>
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        try {
        return results[1] || 0;
        } catch {
            return 0;
        }
    }

    if ($.urlParam('createAtcResourceModal') == '1') {
        $("#createAtcResourceModal").modal();
    }

    @foreach($atcResources as $r)

    if ($.urlParam('editAtcResource{{$r->id}}Modal') == '1') {
        $("#editAtcResource{{$r->id}}Modal").modal();
    }

    @endforeach
</script>

@endsection
