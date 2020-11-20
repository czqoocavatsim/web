@extends('layouts.master', ['solidNavBar' => false])

@section('title', 'ATC Resources - ')
@section('description', 'Sector files and resources for Gander controllers')

@section('content')
<div class="card card-image blue rounded-0">
    <div class="text-white text-left pb-2 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">ATC Resources</h1>
                <p style="font-size: 1.2em;" class="mt-3 mb-0">
                    Official documents and files for use when controlling Gander Oceanic
                </p>
            </div>
        </div>
    </div>
</div>
<div class="container py-4">
    <div class="list-group list-group-flush">
        @foreach ($resources as $resource)
        @if($resource->atc_only)
        @can('view certified only atc resource')
        <div class="list-group-item">
            <div class="row">
                <div class="col"><b>{{$resource->title}} - Certified Controllers Only</b></div>
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
                        {{$resource->html()}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        <a href="{{$resource->url}}" role="button" class="btn btn-success">View</a>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        @else
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
                        {{$resource->html()}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        <a href="{{$resource->url}}" role="button" class="btn btn-success">View</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endsection
