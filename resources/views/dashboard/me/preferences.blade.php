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
            <h4 class="font-weight-bold blue-text">Appearance <span class="badge blue">BETA</span></h4>
            <p>Do you live on the light ☀ or the dark 🌙 side? (Dark mode is not yet complete)</p>
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

    <hr>
    <h5 class="mb-3">Privacy</h5>
    <p class="mt-5 mb-0 text-muted">Changes are automatically saved.</p>
</div>
@endsection
