@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('title', 'Policies - ')
@section('description', 'Policies and guidelines for the operation of Gander Oceanic')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h1 class="font-weight-bold blue-text">Policies</h1>
        <hr>
        @if (Auth::check() && Auth::user()->permissions >= 4)
            <div class="card w-50">
                <div class="card-body">
                    <h5 class="card-title">Policy admin</h5>
                    <a href="#" data-toggle="modal" data-target="#addPolicyModal" class="btn btn-primary">Add policy</a>
                </div>
            </div>
        @endif
        <br class="my-2">
        @foreach ($policies as $policy)
            <div id="accordion">
                <div aria-expanded="true"  class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#policy{{$policy->id}}" aria-expanded="true" aria-controls="policy{{$policy->id}}">
                                {{ $policy->name }}
                            </button>
                        </h5>
                    </div>
                    <div id="policy{{$policy->id}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            @if (Auth::check() && Auth::user()->permissions >= 4)
                                <div class="border" style="padding: 10px;">
                                    <a href="{{url('/policies/'.$policy->id.'/delete')}}" class="btn btn-primary">Delete policy</a>
                                    &nbsp;
                                    <b>Created by {{\App\Models\Users\User::find($policy->author)->fname}} {{\App\Models\Users\User::find($policy->author)->lname}} {{\App\Models\Users\User::find($policy->author)->id}} on {{$policy->releaseDate }}</b>
                                </div>
                            @endif
                            <p>{{$policy->details}}</p>
                            @if ($policy->staff_only == 1)
                                <p>
                                    <b>This is a private staff-only policy.</b>
                                </p>
                            @endif
                            <a target="_blank" href="{{$policy->link}}">Direct Link</a>
                            @if ($policy->embed == 1)
                                <iframe width="100%" style="height: 600px; border: none;" src="{{$policy->link}}"></iframe>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="modal fade" id="addPolicyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['route' => 'policies.create']) !!}
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Details (max 250 chars)</label>
                            {!! Form::textarea('details', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">URL</label>
                            {!! Form::text('link', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Embed</label>
                            {!! Form::select('embed', ['0' => 'No embed', '1' => 'Embed'], ['placeholder' => 'Please choose one..'], ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Privacy</label>
                            {!! Form::select('staff_only', ['0' => 'Public', '1' => 'Private to staff only'], ['placeholder' => 'Please choose one..'], ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Create News Article</label>
                            {!! Form::select('email', ['all' => 'Email all users and news article', 'emailcert' => 'Email certified CZQO controllers and news article', 'newsonly' => 'Only news article', 'none' => 'Nothing at all'], ['placeholder' => 'Please choose one..'], ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Release</label>
                            {!! Form::date('date', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
