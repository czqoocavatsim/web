@extends('layouts.master')
@section('content')
<div class="d-none d-md-block">
    <div class="row">
        <div class="col-md-3">
            <div class="container-fluid grey lighten-3 py-5 px-4">
                <ul class="list-unstyled m-0">
                    <li class="w-100">
                        <div class="grey lighten-2 p-4" style="border-radius: 10px;">
                            <div class="d-flex flex-row">
                                <i style="font-size:20px; margin-right:20px;" class="fas fa-envelope blue-text"></i>
                                <span style="font-size: 1.1em;">Dashboard</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            @yield('training-admin-content')
        </div>
    </div>
</div>
@endsection
