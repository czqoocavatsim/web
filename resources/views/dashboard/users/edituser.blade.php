@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        {!! Form::open(['route' => ['users.edit.store', $user->id]]) !!}
        <a href="{{url('dashboard/users/'.$user->id)}}"><i class="fa fa-left-arrow"></i>Back To User Profile</a>
        <h2>Edit User {{ $user->id }}</h2>
        <h5>{{ $user->fullName('FL')}}</h5>
        <br/>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Attribute</th>
                <th scope="col">Value</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">Permissions</th>
                <td>
                    {!! Form::select('permissions', ['0' => '0 - Guest', '1' => '1 - CZQO Certified', '2' => '2 - Instructor/Mentor/Developer', '3' => '3 - Director (Non Executive)', '4' => '4 - Director (Executive)'], ['placeholder' => 'Please choose one...'], ['class' => 'form-control']) !!}
                </td>
            </tr>
            </tbody>
        </table>
        <small class="text-muted">A permissions guide is available <a href="{{url('/cdn/PermissionsGuide.pdf')}}">here.</a></small>
        <br/><br/>
        {!! Form::submit('Edit User', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
        <a href="{{url('/dashboard/users/' . $user->id)}}" role="button" class="btn btn-outline-light">Cancel Edit</a>
    </div>
@stop