@extends('layouts.master')

@section('content')

@switch($allowed)

@case('true')

<div class="jumbtron" style=" background-size: cover; background-repeat: no-repeat; background-image:url({{asset('img/home-screen-backgrounds/czqosquarelightblue.png')}}); background-position: right;">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-5">
                <h1 class="font-weight-bold blue-text">Apply to join Gander Oceanic</h1>
                <p style="font-size: 1.2em;" class="mt-3">
                    Thank you for choosing to apply to be an oceanic controller with us! By applying for Gander, you have the opportunity to join our exciting community of passionate controllers.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    If you have any questions you would like answered before applying, please don't hestiate to reach out via a support ticket or email. We also have a Discord community, which you can join through myCZQO.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    To be able to apply, you require a C1 rating or higher and at least 80 hours controlling Enroute positions, 25 of which controlling a single Enroute position. If you do not yet meet these requirements, your application may be rejected.
                </p>
                <p style="font-size: 1.4em;" class="mt-3">
                    <a class="blue-text font-weight-bold" href="#applicationBody">Let's begin your application! &nbsp;&nbsp;<i class="fas fa-arrow-down"></i></a>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="container py-4" id="applicationBody">
    <p>Your personal information for this application is automatically collected from your account.</p>
    @if ($errors->applicationErrors->any())
    <div class="alert alert-danger">
        <h4 class="alert-heading">There were errors submitting your application.</h4>
        <ul>
            @foreach ($errors->applicationErrors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {!! Form::open(['id' => 'applicationForm', 'route' => 'training.applications.apply.post']) !!}
    <ul class="mt-0 pt-0 pl-0 stepper stepper-vertical">
        <li class="active">
            <a href="">
                <span class="circle">1</span>
                <span class="label">Why do you wish to be an oceanic controller?</span>
            </a>
            <div class="step-content w-75 grey lighten-3">
                <p>Enter your reason here. We want to here about your motivation for choosing Gander, and what you can bring to our OCA.</p>
                {!! Form::textarea('applicant_statement', null, ['class' => 'w-100', 'id' => 'justificationField', 'onkeyup' => 'countChar(this)']) !!}
                <script>
                    var simplemde = new SimpleMDE({ element: document.getElementById("justificationField"), toolbar:false });
                </script>
            </div>
        </li>
        <li class="active">
            <a href="">
                <span class="circle">2</span>
                <span class="label">Referees</span>
            </a>
            <div class="step-content w-75 grey lighten-3">
                <p>Enter one referee to support your application. This may be one of the following individuals:</p>
                <ul>
                    <li>- Your home FIR or division director/chief</li>
                    <li>- Your home FIR or division training director</li>
                    <li>- Your regional director</li>
                </ul><br>
                <p>We may contact this referee while processing your application.</p>
                <br>
                <div class="form-group">
                    <label for="">Name of referee</label>
                    <input type="text" placeholder="Jane Doe" value="{{old('refereeName')}}" name="refereeName" required id="" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Email of referee</label>
                    <input type="email" placeholder="j.doe@division.com" value="{{old('refereeEmail')}}" required name="refereeEmail" id="" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Staff position of referee</label>
                    <input type="text" placeholder="Division director" value="{{old('refereePosition')}}" required name="refereePosition" id="" class="form-control">
                </div>
            </div>
        </li>
        <li class="active">
            <a href="">
                <span class="circle">3</span>
                <span class="label">Finish your application</span>
            </a>
            <div class="step-content w-75 grey lighten-3">
                <p>By applying to Gander Oceanic you acknowledge the activity requirements for after you receive your endorsement. You will be required to control 3 hours each quarter. Failure to meet the requirement without justification may lead to the revokation of your endorsement.</p>
                <p>You also agree to comply with our General and Training policies.</p>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" required name="agreeActivity" id="agreeActivity">
                    <label class="custom-control-label" for="agreeActivity">I understand</label>
                </div>
            </div>
        </li>
    </ul>
    <button class="btn btn-success" style="font-size: 1.1em; font-weight: 600;"><i class="fas fa-check"></i>&nbsp;&nbsp;Submit Application</button>
    {!! Form::close() !!}
</div>

@break

@case('hours')

<div class="jumbtron" style=" background-size: cover; background-repeat: no-repeat; background-image:url({{asset('img/home-screen-backgrounds/czqosquarelightblue.png')}}); background-position: right;">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-5">
                <h1 class="font-weight-bold red-text">You do not yet meet the requirements to apply</h1>
                <p style="font-size: 1.2em;" class="mt-3">
                    Thank you for showing your interest in becoming a Gander Oceanic controller. Unfortunately, you do not yet meet our application requirements.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    To apply, you require <strong>80 hours</strong> on your <strong>C1+ rating controlling Enroute positions, 25 of which controlling a single Enroute position.</strong> You currently have {{$hoursTotal}} hours towards that requirement.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    If you believe there has been an error, please contact us so that we can investigate.
                </p>
            </div>
        </div>
    </div>
</div>

@break


@case('rating')

<div class="jumbtron" style=" background-size: cover; background-repeat: no-repeat; background-image:url({{asset('img/home-screen-backgrounds/czqosquarelightblue.png')}}); background-position: right;">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-5">
                <h1 class="font-weight-bold red-text">You do not yet meet the requirements to apply</h1>
                <p style="font-size: 1.2em;" class="mt-3">
                    Thank you for showing your interest in becoming a Gander Oceanic controller. Unfortunately, you do not yet meet our application requirements.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    To be able to apply, you require a C1 rating or higher and at least 80 hours controlling Enroute positions, 25 of which controlling a single Enroute position.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    If you believe there has been an error, please contact us so that we can investigate.
                </p>
            </div>
        </div>
    </div>
</div>

@break

@case('shanwick')

<div class="jumbtron" style=" background-size: cover; background-repeat: no-repeat; background-image:url({{asset('img/home-screen-backgrounds/czqosquarelightblue.png')}}); background-position: right;">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-5">
                <h1 class="font-weight-bold blue-text">You are already certified to control through VATUK</h1>
                <p style="font-size: 1.2em;" class="mt-3">
                    Thank you for showing your interest in becoming a Gander Oceanic controller. As we have detected you are already endorsed to control Shanwick/Gander Oceanic through the VATUK Training Department, you do not require a Gander Oceanic endorsement.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    VATUK certified controllers can enjoy the same participation in Gander Oceanic's community and events as those certified here. If you are unable to access resources for certified controllers, please contact us.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    If you believe there has been an error, please contact us so that we can investigate.
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    <a class="font-weight-bold text-body" href="https://cts.vatsim.uk/home/validations.php?view=16">View VATUK Shanwick roster &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                </p>
            </div>
        </div>
    </div>
</div>
@break

@endswitch

@endsection
