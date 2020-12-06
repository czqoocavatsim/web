@extends('admin.settings.layouts.main')

@section('settings-content')
    <div class="container py-4">
        <h1 class="blue-text font-weight-bold mt-2">Site information</h1>
        <form method="POST" action="{{route('settings.siteinformation.post')}}">
            @csrf
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 25%;" scope="col">Variable</th>
                        <th scope="col">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">
                        System Name
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->sys_name}}" name="sys_name" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Version Number
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->release}}" name="release" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Build Date
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->sys_build}}" name="sys_build" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Copyright Year
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->copyright_year}}" name="copyright_year" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="Save" class="btn btn-sm btn-primary">
        </form>
    </div>
@stop
