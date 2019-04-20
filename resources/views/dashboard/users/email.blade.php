@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Email {{ $user->fname }} {{ $user->lname }} {{ $user->id }}</h2>
        <br/>
        {!! Form::open(['route' => 'users.email.store']) !!}
        <div class="form-group">
            <h6>Subject</h6>
            {!! Form::text('subject', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <h6>Message</h6>
            {!! Form::textarea('msg', null, ['class' => 'form-control']) !!}
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <h4 class="alert-heading">There were errors submitting your feedback.</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@stop