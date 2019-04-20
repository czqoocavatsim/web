@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>CZQO Controller Roster</h2>
        <h5>Staff View</h5>
        <br/>
        <div class="row">
            <div class="col">
                <table class="table table-hover table-responsive">
                    <thead>
                        <th scope="col">Full Name</th>
                        <th scope="col">CID</th>
                        <th scope="col">Status</th>
                        <th scope="col">Division</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </thead>
                    <tbody>
                        @foreach ($controllers as $controller)
                            <tr>
                            <td scope="row">Test</td>
                            <td>123123123</td>
                            <td>asdasd</td>
                            <td>asdasd</td>
                            <td>
                                <a href="#">Edit</a>
                            </td>
                            <td>
                                <a href="#" class="text-danger">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop