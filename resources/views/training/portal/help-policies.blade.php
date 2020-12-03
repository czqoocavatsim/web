@extends('training.portal.layouts.main')
@section('page-header-title', 'Help and Policies')
@section('portal-content')
<div class="container py-4">
    <h3 class="blue-text mb-3">Need support?</h3>
    <p>We're always here to assist you. Feel free to contact the Chief Instructor or Assistant Chief Instructor via email for assistance with any enquries you may have.</p>
    <p style="font-size: 1.2em;" class="mt-3">
        <a class="font-weight-bold text-body" href="{{route('staff')}}">Find their emails &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
    </p>
    <h3 class="blue-text mt-4 mb-3">Frequently asked questions</h3>
    <p><i>Coming soon</i></p>
    <h3 class="blue-text mt-4 mb-3">Policies</h3>
    <p>Gander Oceanic operates on several policies governing the training process and expectations for our controllers.</p>
    <div class="list-group z-depth-1">
        @foreach ($policies as $policy)
        <div class="list-group-item waves-effect">
            <div class="row">
                <div class="col">{{$policy->title}} Policy</div>
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
    <h3 class="blue-text mt-4 mb-3">Formal complaints</h3>
    <p>If you have a formal complaint about one of our processes or a person in our staff, please contact the OCA Chief in confidence with your concerns.</p>
    <p style="font-size: 1.2em;" class="mt-3">
        <a class="font-weight-bold text-body" href="{{route('staff')}}">Contact details &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
    </p>
</div>
@endsection
