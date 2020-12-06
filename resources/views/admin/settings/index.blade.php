@extends('admin.settings.layouts.main')

@section('settings-content')
    <h1 class="blue-text font-weight-bold mb-3">Settings</h1>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="list-group-item h-100 p-4 z-depth-1 shadow-none">
                <h3 class="blue-text">Site information</h3>
                <p>Version, copyright, etc.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.siteinformation')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="list-group-item h-100 p-4 z-depth-1 shadow-none">
                <h3 class="blue-text">Emails</h3>
                <p>Set website email addresses.&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.emails')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="list-group-item h-100 p-4 z-depth-1 shadow-none">
                <h3 class="blue-text">Rotation images</h3>
                <p>Banner used throughout the site.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.rotationimages')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="list-group-item h-100 p-4 z-depth-1 shadow-none">
                <h3 class="blue-text">Activity Log</h3>
                <p>Log of all events.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.activitylog')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="list-group-item h-100 p-4 z-depth-1 shadow-none">
                <h3 class="blue-text">Staff</h3>
                <p>Who's on the team? Find out here.<br>&nbsp;</p>
                <a class="black-text font-weight-bold" href="{{route('settings.staff')}}">Go <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
@endsection
