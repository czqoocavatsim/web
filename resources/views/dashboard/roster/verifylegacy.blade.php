@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Verify certified status</h2>
        @if ($status == "verified")
            <div class="alert alert-success">
                <h3 class="alert-heading">Success!</h3>
                <p>
                    You were verified as a Gander certified controller.
                </p>
            </div>
        @else
            <div class="alert alert-danger">
                <h3 class="alert-heading">Oh no...</h3>
                <p>
                    You were not verified as a Gander certified controller.
                    <br/>
                    If you believe this is a mistake, please <a href="{{url('/dashboard/feedback')}}" class="alert-link">contact us.</a>
                </p>
            </div>
        @endif
        <br/>
        <a href="{{url('/dashboard')}}" class="btn btn-primary" role="button">Go back to Dashboard</a>
    </div>
@stop