@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="container" style="margin-top: 20px;">
        <a href="{{route('application.list')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Applications</a>
    <h1 class="blue-text font-weight-bold mt-2">Start an application</h1>
    <hr>
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
            <p>Please also note the following requirements which will be manually verified:</p>
            <ul>
                <li>25 hours spent controlling an enroute control position.</li>
            </ul>
            <p>If you are an existing Shanwick controller, please email chief(at)ganderoceanic.com if you would like your endorsement transferred.</p>
        </div>
        @if ($errors->applicationErrors->any())
            <div class="alert alert-danger">
                <h4 class="alert-heading">One or more errors occured whilst attempting to submit your application.</h4>
                <ul>
                    @foreach ($errors->applicationErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::open(['route' => 'application.submit']) !!}
        <ul class="mt-0 pt-0 pl-0 stepper stepper-vertical">
            <li class="active">
                <a href="">
                    <span class="circle">1</span>
                    <span class="label">Why would you like to be an oceanic controller?</span>
                </a>
                <div class="step-content w-75 grey lighten-3">
                    <p>Please type here (minimum 50 words):</p>
                    {!! Form::textarea('applicant_statement', null, ['class' => 'w-100', 'id' => 'justificationField', 'onkeyup' => 'countChar(this)']) !!}
                    <script>
                        var simplemde = new SimpleMDE({ element: document.getElementById("justificationField"), toolbar:false });
                    </script>
                </div>
            </li>{{--
            <li class="active">
                <a href="">
                    <span class="circle">2</span>
                    <span class="label">Referees</span>
                </a>
                <div class="step-content w-75 grey lighten-3">
                    <p>Please mention one referee to support your application. This may be one of the following individuals:</p>
                    <ul>
                        <li>Your home FIR Chief or Division Director</li>
                        <li>Your home FIR Chief or Division Training Director</li>
                        <li>Your Regional Director</li>
                        <li>A member of the Gander Oceanic Staff Team</li>
                    </ul>
                    <br>
                    <div class="form-group">
                        <label for="">Referee's Name</label>
                        <input type="text" placeholder="Jane Doe" name="refereeName" required id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Referee's Email</label>
                        <input type="email" placeholder="j.doe@division.com" required name="refereeEmail" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Referee's Staff Position</label>
                        <input type="text" placeholder="Division director" required name="refereePosition" id="" class="form-control">
                    </div>
                </div>
            </li> --}}
            <li class="active">
                <a href="">
                    <span class="circle">2</span>
                    <span class="label">Finish your application</span>
                </a>
                <div class="step-content w-75 grey lighten-3">
                    <h5>Activity requirements</h5>
                    <p>By applying to Gander Oceanic you acknowledge that you agree to be bound to the following activity requirements upon receipt of your endorsement. You will be required to control 6 hours bi-annually (every half-year). Failure to do so may result in the revokation of your endorsement.</p>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" required name="agreeActivity" id="agreeActivity">
                        <label class="custom-control-label" for="agreeActivity">I understand</label>
                    </div>
                </div>
            </li>
        </ul>
        {!! Form::submit('Submit Your Application', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    @elseif ($allowed == "false")
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">You are not eligible to apply.</h4>
            <p>You are not yet a C1 controller or higher. Please check back when you have a C1 rating and have obtained:</p>
            <ul>
                <li>80 hours on your C1 or above ratings.</li>
                <li>25 hours spent controlling a single enroute control position.</li>
            </ul>
            <p>If you believe there is an error, please <a href="{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Issue with requirement check on application system'])}}">start a support ticket.</a></p>
        </div>
    @elseif ($allowed == "pendingApplication")
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">You already have another pending application.</h4>
            <p>Please wait for this application to be processed. Processing times may take up to 48 hours.</p>
            <p>If you believe there is an error, please <a href="{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Issue with pending check on application system'])}}">start a support ticket.</a></p>
        </div>
    @elseif ($allowed == "hours")
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">You are not eligible to apply.</h4>
            <p>You do not yet have 80 hours or above on your C1 or above ratings. Please check back when you have obtained:</p>
            <ul>
                <li>80 hours on your C1 or above ratings.</li>
                <li>25 hours spent controlling a single enroute control position.</li>
            </ul>
            <p>You currently have <a title="View your hours rating by rating" href="{{$url}}" target="_blank">{{$total}} hours</a> towards the requirements.</p>
            <p>If you believe there is an error, please <a href="{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Issue with hour requirement check on application system'])}}">start a support ticket.</a></p>
        </div>
    @else
        <b>You are not eligible to apply, but we're not sure why. Please contact the FIR Chief for further assistance.</b>
    @endif
</div>
@stop
