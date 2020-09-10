@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1 class="blue-text font-weight-bold mt-2">Email Preferences</h1>
        <hr>
        <h5>Current subscription status:</h5>
        @if (Auth::user()->gdpr_subscribed_emails == 0)
            <h3>
                <span class="badge badge-danger">Not subscribed</span>
            </h3>
        @else
            <h3>
                <span class="badge badge-success">Subscribed</span>
            </h3>
        @endif
        <br/>
        <h4>What does this mean?</h4>
        <p>
            By subscribing to emails, you allow Gander Oceanic OCA to send you what the EU GDPR describes as "promotional" emails.
            These emails are typically not necessary for your continued participation in the OCA or to hold an account on the Core system.<br/>
            Some examples could include:
        </p>
        <ul style="list-style: square">
            <li>Controller certifications for the month</li>
            <li>News from the OCA Chief about non-critical matters</li>
            <li>Updates from other staff members</li>
        </ul>
        <p><br/>
            To see more info, check out our <a href="{{url('/privacy')}}">privacy policy!</a>
        </p><br/>
        <h4>Subscribe</h4>
        <br/>
        <a role="button" class="btn btn-success" href="{{url('/dashboard/emailpref/subscribe')}}">Subscribe to emails</a>
        <a role="button" class="btn btn-danger" href="{{url('/dashboard/emailpref/unsubscribe')}}">Unsubscribe from emails</a>
    </div>
@stop
