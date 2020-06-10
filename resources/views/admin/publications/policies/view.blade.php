@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('publications.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Publications</a>
    <h1 class="font-weight-bold blue-text">Policy: {{$policy->title}}</h1>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('publications.policies.create.post')}}" enctype="multipart/form-data">
            @csrf
            @if($errors->createPolicyErrors->any())
            <div class="alert alert-danger">
                <h4>There were errors submitting the policy</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createPolicyErrors->all() as $error)
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
                            <label for="">Title</label>
                            <p>{{$policy->title}}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Created at</label>
                            <p>{{$policy->created_at->toDayDateTimeString()}}</p>
                        </div>
                        <div class="form-group">
                            <label for="">PDF URL</label>
                            <p>
                                <a href="{{$policy->url}}">{{$policy->url}}</a>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Description</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <p>
                            {{$policy->descriptionHtml()}}
                        </p>
                    </div>
                </li>
            </ul>
        </form>
        </div>
    </div>
</div>
@endsection
