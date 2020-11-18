@extends('layouts.master', ['solidNavBar' => false])
@section('title', 'Policies - ')
@section('description', 'Policies and guidelines for operations in Gander Oceanic')

@section('content')
    <div class="card card-image blue rounded-0">
        <div class="text-white text-left pb-2 pt-5 px-4">
            <div class="container">
                <div class="py-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">Policies</h1>
                    <p style="font-size: 1.2em;" class="mt-3 mb-0">
                        Policies and guidelines for operations in Gander Oceanic. These policies may be updated from time to time.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="list-group list-group-flush">
            @foreach ($policies as $policy)
            <div class="list-group-item">
                <div class="row">
                    <div class="col">{{$policy->title}}</div>
                    <div class="col-sm-4">
                        <a data-policy-id="{{$policy->id}}" href="javascript:void(0)" class="expandHidePolicyButton"><i class="fa fa-eye"></i>&nbsp;View Policy and Description</a>
                    </div>
                </div>
                <div class="pt-2 d-none" id="policyEmbed{{$policy->id}}">
                    <p>
                        {{$policy->descriptionHtml()}}
                    </p>
                    <a href="{{$policy->url}}" target="_blank">Direct Link to PDF</a>
                    <iframe width="100%" style="height: 600px; border: none;" src="{{$policy->url}}"></iframe>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop
