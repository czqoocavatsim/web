@extends('layouts.master')

@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="blue-text font-weight-bold mt-2">Preferences</h1>
    <hr>
    <p>Customise your Gander Oceanic web experience.</p>
    <h3>Enable beta features</h3>
    <select name="" value="{{$preferences->enable_beta_components}}" id="" class="custom-select">
        <option value="1">Enabled</option>
        <option value="0">Disabled</option>
    </select>
</div>
@endsection
