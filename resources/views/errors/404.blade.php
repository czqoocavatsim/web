@extends('layouts.primary')
@section('title', 'Error 404 - ')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h1 class="fw-900 blue-text" style="font-size: 3em;">🔍 &nbsp;Nothing in sight...</h1>
                <h4 class="fw-700 my-4">Error 404 Page Not Found</h4>
                <div class="mt-4 fw-400">
                    <p style="font-size: 1.2em;">
                        We couldn't find anything at <a href="{{Request::url()}}">{{Request::url()}}</a>.
                        <br>
                        If you believe this is a mistake, please contact us.
                    </p>
                </div>
                <a href="{{URL::previous()}}" class="btn mt-3 bg-czqo-blue-light">Go Back</a>
            </div>
            <div class="d-md-block d-none">
                <i class="fas fa-exclamation-triangle blue-text" style="display:inline; font-size: 50em; opacity: 0.2"></i>
            </div>
        </div>
    </div>
@endsection
