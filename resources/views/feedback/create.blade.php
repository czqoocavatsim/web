@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <h1 class="font-weight-bold blue-text">Submit Feedback</h1>
        <p style="font-size: 1.2em;">
            Here you can submit feedback on our controlling or operations at Gander Oceanic.
        </p>
        <hr>
        @if($errors->createFeedbackErrors->any())
            <div class="alert alert-danger">
                <h4>Error</h4>
                <ul class="pl-0 ml-0" style="list-style:none;">
                    @foreach ($errors->createFeedbackErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('feedback.create.post')}}" method="POST">
            @csrf
            <ul class="mt-0 pt-0 pl-0 stepper stepper-vertical">
                <li class="active">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Type of feedback</span>
                    </a>
                    <div class="step-content w-75 grey lighten-3">
                        <p>Please select the type of feedback you are submitting.</p>
                        <select name="feedbackType" id="feedbackTypeSelect" class="form-control">
                            <option hidden>Please select one...</option>
                            <option value="controller">Controller Feedback</option>
                            <option value="operational">Operations Feedback</option>
                            <option value="website">Website Feedback</option>
                        </select>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Your message</span>
                    </a>
                    <div id="typeNotSelected" class="step-content w-75 grey lighten-3">
                        Please select a feedback type before continuing.
                    </div>
                    <div id="typeSelected" class="step-content w-75 grey lighten-3" style="display:none">
                        <div class="md-form" id="controllerCidGroup" style="display:none">
                            <input type="text" name="controllerCid" class="form-control">
                            <label>Controller CID</label>
                        </div>
                        <div class="md-form" id="subjectGroup" style="display:none">
                            <input type="text" name="subject" class="form-control">
                            <label>Subject</label>
                        </div>
                        <div id="contentGroup">
                            <label style="font-size: 1rem;">Your feedback</label>
                            <textarea class="form-control" name="content" class="w-75"></textarea>
                        </div>
                    </div>
                </li>
            </ul>
            <button class="btn btn-success" style="font-size: 1.1em; font-weight: 600;"><i class="fas fa-check"></i>&nbsp;&nbsp;Submit Feedback</button>
        </form>
    </div>
    <script>
        /*
        Show/hide message form bsaed on whether the user has selected a feedback type
        */
        $("#feedbackTypeSelect").on('change', function() {
            if (this.value) {
                $("#typeNotSelected").hide();
                $("#typeSelected").show();
            }
        })

        /*
        Feedback type select to disable/enable the CID field and subject field
         */
        $('#feedbackTypeSelect').on('change', function() {
            //Figure out what it is
            if (this.value == 'controller') {
                //Enable CID disable subject
                $("#controllerCidGroup").show();
                $("#subjectGroup").hide();
            } else {
                //Maybe not
                $("#controllerCidGroup").hide();
                $("#subjectGroup").show();
            }
        })
    </script>
@endsection
