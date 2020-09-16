@extends('layouts.master')

@section('content')
<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="font-weight-bold blue-text">Preferences</h1>
    <p style="font-size: 1.2em;">
        Customise your experience
    </p>
    <hr>
    <form action="{{route('me.preferences.post')}}" method="POST">
        @if($errors->savePreferencesErrors->any())
            <div class="alert alert-danger">
                <h4>One or more errors occurred whilst saving your preferences</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->savePreferencesErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @csrf
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h4 class="font-weight-bold blue-text">UI Mode <span class="badge blue">BETA</span></h4>
                <p>Do you live on the light ☀ or the dark 🌙 side? (Dark mode is not yet complete)</p>
            </div>
            <div style="width: 25%;">
                <select name="ui_mode" id="" class="form-control">
                    <option value="light" selected>Light mode</option>
                    <option value="dark">Dark mode</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary mt-4">Save Settings</button>
    </form>
    <h3 class="font-weight-bold blue-text mt-4">Current email subscription status</h3>
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
        When you subscribe to emails, you allow Gander Oceanic OCA to send you "promotional" emails as described in the EU GDPR.
        These emails are not necessary for your continued participation in the OCA or to continue holding an account on the Core system.<br/>
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
@endsection
