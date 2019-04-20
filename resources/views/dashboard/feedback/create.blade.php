@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <script>
    tinymce.init({
      selector: '#textarea',
      toolbar: false
    });
    </script>
    <div class="container" style="margin-top: 20px;">
        <h2>Send Feedback</h2><hr>
        {!! Form::open(['route' => 'feedback.store']) !!}
        <div class="form-group">
            <h6>Who is your feedback directed to?</h6>
            {!! Form::select('department', ['Web' => 'Webmaster/Website Management', 'ATCFeedback' => 'Controller Feedback/Complaints', 'Director' => 'Operations Director', 'Other' => 'Other'], ['placeholder' => 'Please choose one...'], ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <h6>What is your feedback?</h6>
            {!! Form::textarea('msg', null, ['class' => '', 'id' => 'textarea']) !!}
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
