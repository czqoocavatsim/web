@extends('layouts.master')

@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="font-weight-bold blue-text">Preferences</h1>
    <p style="font-size: 1.2em;">
        Customise your experience
    </p>
    <hr>
    <form action="{{route('me.preferences.post')}}" method="POST">
        @if($errors->savePreferencesErrors->any())
            <div class="alert alert-danger">
                <h4>There were errors saving your preferences</h4>
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
                <h4 class="font-weight-bold blue-text">UI Mode</h4>
                <p>Do you live on the light ☀ or the dark 🌙 side?</p>
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
</div>
@endsection
