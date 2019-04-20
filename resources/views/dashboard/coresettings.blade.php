@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Core Settings</h2>
        <br/>
        {!! Form::open(['route' => 'coresettings.store']) !!}
        <table class="table">
            <thead>
                <th scope="col">Variable</th>
                <th scope="col">Value</th>
            </thead>
            <tbody>
                <tr>
                    <th scope="col">System Name</th>
                    <td>
                        {!! Form::text('sys_name', $settings->sys_name, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Release</th>
                    <td>
                        {!! Form::text('release', $settings->release, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Build</th>
                    <td>
                        {!! Form::text('sys_build', $settings->sys_build, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Copyright Year</th>
                    <td>
                        {!! Form::text('copyright_year', $settings->copyright_year, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col"><i>Emails</i></th>
                </tr>
                <tr>
                    <th scope="col">FIR Chief</th>
                    <td>
                        {!! Form::text('emailfirchief', $settings->emailfirchief, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Dep. FIR Chief</th>
                    <td>
                        {!! Form::text('emaildepfirchief', $settings->emaildepfirchief, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Chief Insturctor</th>
                    <td>
                        {!! Form::text('emailcinstructor', $settings->emailcinstructor, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Event Coordinator</th>
                    <td>
                        {!! Form::text('emaileventc', $settings->emaileventc, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Facility Engineer</th>
                    <td>
                        {!! Form::text('emailfacilitye', $settings->emailfacilitye, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="col">Webmaster</th>
                    <td>
                        {!! Form::text('emailwebmaster', $settings->emailwebmaster, ['class' => 'form-control']) !!}
                    </td>
                </tr>
            </tbody>
        </table>
        @if ($errors->any())
            <div class="alert alert-danger">
                <h4 class="alert-heading">There were errors submitting</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
        <hr/>
        <a href="{{url('/dashboard/coresettings/entermaintenance')}}" role="button" class="btn btn-danger">Enter Maintenance</a>
        <br/>
        <a href="{{url('/nickxenophonssabest')}}">Webmaster Portal</a>
    </div>
@stop