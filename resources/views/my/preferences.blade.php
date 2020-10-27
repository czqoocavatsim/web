@extends('layouts.master')

@section('content')
<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
    <h1 class="font-weight-bold blue-text">Preferences</h1>
    <p style="font-size: 1.2em;">
        Customise your experience
    </p>
    <hr>
    <h5 class="mb-3">User Interface</h5>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Appearance</h4>
            <p>Do you live on the light ☀ or the dark 🌙 side?</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Appearance" data-table="main" name="ui_mode" id="" class="form-control pref-dropdown">
                <option value="light" @if($preferences->ui_mode == 'light') selected @endif>Light</option>
                <option value="dark" @if($preferences->ui_mode == 'dark') selected @endif>Dark</option>
            </select>
            <div class="d-none float-right" id="ui_mode_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between mt-2">
        <div>
            <h4 class="font-weight-bold blue-text">Accent Colour</h4>
            <p>Choose your flavour of text colour and backgrounds</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Accent colour" data-table="main" name="accent_colour" id="" class="form-control pref-dropdown">
                <option value="default" @if($preferences->accent_colour == 'default') selected @endif>Gander Blue</option>
                <option value="red" @if($preferences->accent_colour == 'red') selected @endif>Red</option>
                <option value="pink" @if($preferences->accent_colour == 'pink') selected @endif>Pink</option>
                <option value="light-pink" @if($preferences->accent_colour == 'light-pink') selected @endif>Light Pink</option>
                <option value="purple" @if($preferences->accent_colour == 'purple') selected @endif>Purple</option>
                <option value="green" @if($preferences->accent_colour == 'green') selected @endif>Green</option>
                <option value="orange" @if($preferences->accent_colour == 'orange') selected @endif>Orange</option>
            </select>
            <div class="d-none float-right" id="accent_colour_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h5 class="mb-3">Notifications</h5>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Training notifications</h4>
            <p>Updates on scheduled sessions with instructors, etc</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Training notifications" data-table="notifications" name="training_notifications" id="" class="form-control pref-dropdown">
                <option value="email">Email only</option>
                <option @if(!Auth::user()->hasDiscord()) disabled @endif value="email+discord">Email and Discord DMs @if(!Auth::user()->hasDiscord()) (Please link your Discord account to select this option) @endif</option>
            </select>
            <div class="d-none float-right" id="training_notifications_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Event notifications</h4>
            <p>Updates on the latest events and event updates</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Event notifications" data-table="notifications" name="event_notifications" id="" class="form-control pref-dropdown">
                <option value="off">Off</option>
                <option value="email">Email</option>
            </select>
            <div class="d-none float-right" id="event_notifications_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">News notifications</h4>
            <p>The latest news from Gander Oceanic</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="News notifications" data-table="notifications" name="news_notifications" id="" class="form-control pref-dropdown">
                <option value="off">Off</option>
                <option value="email">Email</option>
            </select>
            <div class="d-none float-right" id="news_notifications_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>{{--
    <p style="font-size: 1em;" class="mt-3">
        <a style="text-decoration: underline; text-decoration-style:dotted;" class="text-body" href="#"><i class="fas fa-question blue-text"></i>&nbsp;&nbsp;What these notification types mean</a>
    </p> --}}
    <hr>
    <h5 class="mb-3">Privacy</h5>
    <p class="border my-3 p-4">These preferences are not implemented yet.</p>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Avatar</h4>
            <p>Do you want others to be able to see your avatar?</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Avatar privacy" data-table="privacy" name="avatar_public" id="" class="form-control pref-dropdown">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <div class="d-none float-right" id="avatar_public_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Biography</h4>
            <p>Do you want others to be able to see your biography?</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Biography privacy" data-table="privacy" name="biography_public" id="" class="form-control pref-dropdown">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <div class="d-none float-right" id="biography_public_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Session logs</h4>
            <p>Do you want others to be able to see your session logs?</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Session logs privacy" data-table="privacy" name="session_logs_public" id="" class="form-control pref-dropdown">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <div class="d-none float-right" id="session_logs_public_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between">
        <div>
            <h4 class="font-weight-bold blue-text">Certification details</h4>
            <p>Do you want others to be able to see your certification details (date certified for example)?</p>
        </div>
        <div style="width: 25%;">
            <select data-pretty-name="Certification details privacy" data-table="privacy" name="certification_details_public" id="" class="form-control pref-dropdown">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <div class="d-none float-right" id="certification_details_public_loading">
                <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-circle-notch fa-spin blue-text mr-3" style="font-size:30px;"></i>Saving...
                </div>
            </div>
        </div>
    </div>{{--
    <p style="font-size: 1em;" class="mt-3">
        <a style="text-decoration: underline; text-decoration-style:dotted;" class="text-body" href="#"><i class="fas fa-question blue-text"></i>&nbsp;&nbsp;More about privacy options</a>
    </p> --}}
    <p class="mt-5 mb-0 text-muted">Changes are automatically saved.</p>
</div>
@endsection
