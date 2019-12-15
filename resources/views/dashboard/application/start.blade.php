@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <h3>Apply for Gander Oceanic Certification</h3>
    <p>Your personal information is automatically gathered from your VATSIM SSO login.</p>
    <script src="http://code.jquery.com/jquery-1.5.js"></script>
    <script>
        function countChar(val) {
            var len = val.value.length;
            if (len > 550){
                $('#charNum').text(len + ' characters (Too many)');
            }
            else if (len < 100){
                $('#charNum').text(len + ' characters (Too little)');
            }else {
                $('#charNum').text(len + ' characters');
            }
        }
    </script>
    @if ($allowed == 'true')
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">You are eligibile to apply!</h4>
            <p>Please note this only takes into account your controller rating. Please also note the following requirements which will be manually verified:</p>
            <ul>
                <li>50 hours spent controlling an enroute control position.</li>
            </ul>
        </div>
        {!! Form::open(['route' => 'application.submit']) !!}
        <script>
            tinymce.init({
                selector: '#justificationField',
                menubar: 'false',
                setup: function (editor) {
                    editor.on('keyup', function (e) {
                        countChar(e);
                    });
                }
            });
        </script>
        <div class="form-group">
            <label for="justification">Why do you wish to be an oceanic controller with Gander Oceanic?</label>
            {!! Form::textarea('applicant_statement', null, ['class' => 'form-control', 'id' => 'justificationField', 'onkeyup' => 'countChar(this)']) !!}
            <small class="text-muted" id="charNum"></small>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <h4 class="alert-heading">There were errors submitting your application.</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    @elseif ($allowed == "false")
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">You are not eligible to apply.</h4>
            <p>You are not yet a C1 controller or above. Please check back when you have a C1 rating and you have:</p>
            <ul>
                <li>120 hours on your C1 or above ratings.</li>
                <li>50 hours spent controlling an enroute control position.</li>
            </ul>
            <p>If you believe there is an error, please <a href="{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Issue with requirement check on application system'])}}">start a support ticket.</a></p>
        </div>
    @elseif ($allowed == "pendingApplication")
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">You already have another pending application.</h4>
            <p>Please wait for this application to be processed. Processing times are roughly up to 48 hours.</p>
            <p>If you believe there is an error, please <a href="{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Issue with pending check on application system'])}}">start a support ticket.</a></p>
        </div>
    @elseif ($allowed == "hours")
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">You are not eligible to apply.</h4>
            <p>You do not yet have 120 hours or above on your C1 or above ratings. Please check back when you have:</p>
            <ul>
                <li>120 hours on your C1 or above ratings.</li>
                <li>50 hours spent controlling an enroute control position.</li>
            </ul>
            <p>You currently have <a title="View your hours rating by rating" href="{{$url}}" target="_blank">{{$total}} hours</a> towards the requirements.</p>
            <p>If you believe there is an error, please <a href="{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Issue with hour requirement check on application system'])}}">start a support ticket.</a></p>
        </div>
    @else
        <b>You are not eligible to apply, but we're not sure why. Please contact the FIR Chief for further assistance.</b>
    @endif
</div>
@stop
