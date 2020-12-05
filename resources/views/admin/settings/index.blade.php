@extends('admin.settings.layouts.main')

@section('settings-content')
    <h1 class="blue-text font-weight-bold mb-3">Settings</h1>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                <h3>Site information</h3>
                <p>Version, copyright, etc.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.siteinformation')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                <h3>Emails</h3>
                <p>Set website email addresses.&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.emails')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                <h3>Rotation images</h3>
                <p>Banner used throughout the site.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.rotationimages')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                <h3>Activity Log</h3>
                <p>Log of all events.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.activitylog')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                <h3>Staff</h3>
                <p>Who's on the team? Find out here.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.staff')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
@endsection
