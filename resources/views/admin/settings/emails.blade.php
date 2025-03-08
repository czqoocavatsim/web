@extends('admin.settings.layouts.main')

@section('settings-content')
    <div class="container py-4">
        <h1 class="blue-text font-weight-bold mt-2">Emails</h1>
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
                        FIR Director
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emailfirchief}}" name="emailfirchief" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Operations Director
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emaildepfirchief}}" name="emaildepfirchief" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        Events & Training Director
                    </th>
                    <td>
                        <input required type="text" value="{{$coreSettings->emailcinstructor}}" name="emailcinstructor" id="" class="form-control form-control-sm border">
                    </td>
                    </tr>
                    <tr>
                    <th scope="row">
                        IT Director
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
