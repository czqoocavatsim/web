@extends('layouts.primary', ['solidNavBar' => false])
@section('title', 'Policies - ')
@section('description', 'Policies and guidelines for operations in Gander Oceanic')

@section('content')
    <div class="jarallax card card-image blue rounded-0"  data-jarallax data-speed="0.2">
    {{-- <img class="jarallax-img" src="{{asset('assets/resources/media/img/website/euroscope_client.png')}}" alt=""> --}}
        <div class="text-white text-left rgba-stylish-strong py-3 pt-5 px-4">
            <div class="container">
                <div class="py-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">Policies</h1>
                    <h4>Policies and guidelines for operations in Gander Oceanic..</h4>
                    <p>Updates to these policies will be announced on the Gander Oceanic Website & Discord.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="list-group">
            @foreach ($policies as $policy)
            <div class="list-group-item z-depth-1 rounded shadow-none mb-3 p-3">
                <div class="d-flex flex-row justify-content-between">
                    <div class=" fw-700">{{$policy->title}}</div>
                    <div class="fw-700">
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
