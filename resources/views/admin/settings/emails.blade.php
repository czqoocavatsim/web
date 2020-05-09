@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <a href="{{route('settings.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Settings</a>
        <h1 class="blue-text font-weight-bold mt-2">Emails</h1>
        <hr>
        <form method="POST" action="{{route('settings.emails.post')}}">
            @csrf
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 25%;" scope="col">Position / Department</th>
                        <th scope="col">Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">
                        FIR Chief
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emailfirchief}}" name="emailfirchief" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Deputy FIR Chief
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emaildepfirchief}}" name="emaildepfirchief" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Chief Instructor
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emailcinstructor}}" name="emailcinstructor" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Events and Marketing
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emaileventc}}" name="emaileventc" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Operations
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emailfacilitye}}" name="emailfacilitye" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Webmaster
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emailwebmaster}}" name="emailwebmaster" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="Save" class="btn btn-sm btn-primary">
        </form>
    </div>
@stop
