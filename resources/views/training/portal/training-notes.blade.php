@extends('training.portal.layouts.main')
@section('page-header-title', 'Your training notes')
@section('portal-content')
@if (count($notes) == 0) None yet! @endif
<div class="list-group list-group-flush z-depth-1 rounded mt-4">
    @foreach ($notes as $note)
    <div class="list-group-item">
        <div class="d-flex flex-row justify-content-between">
            <div>{{$note->created_at->toFormattedDateString()}}</div>
            <div>
                <a data-policy-id="{{$note->id}}" href="javascript:void(0)" class="expandHidePolicyButton"><i class="fa fa-eye"></i>&nbsp;View</a>
            </div>
        </div>
        <div class="d-none" id="policyEmbed{{$note->id}}">
            <div class="d-flex flex-row justify-content-between">
                <p class="text-muted mt-3">Last edited <span style="text-decoration: underline; text-decoration-style:dotted; cursor: help;" title="{{$note->updated_at ?? ''}}">{{$note->updated_at ? $note->updated_at->diffForHumans() : 'never'}}</span></p>
            </div>
            <hr>
            <p>
                {{$note->contentHtml()}}
            </p>
        </div>
    </div>
    @endforeach
</div>

<h4 class="blue-text mt-4">Instructor Recommendations</h4>
@if (count($recommendations) == 0) None yet! @endif
<div class="list-group list-group-flush z-depth-1 rounded mt-4">
    @foreach ($recommendations as $note)
    <div class="list-group-item">
        <div class="d-flex flex-row justify-content-between">
            <div>{{$note->created_at->toFormattedDateString()}} - {{$note->type}}</div>
            <div>
                <a data-policy-id="{{$note->id}}9999" href="javascript:void(0)" class="expandHidePolicyButton"><i class="fa fa-eye"></i>&nbsp;View</a>
            </div>
        </div>
        <div class="d-none" id="policyEmbed{{$note->id}}9999">
            <div class="d-flex flex-row justify-content-between">
                <p class="text-muted mt-3">Last edited <span style="text-decoration: underline; text-decoration-style:dotted; cursor: help;" title="{{$note->updated_at ?? ''}}">{{$note->updated_at ? $note->updated_at->diffForHumans() : 'never'}}</span></p>
            </div>
            <hr>
            <p>
                {{$note->type}}
            </p>
        </div>
    </div>
    @endforeach
</div>
@endsection
