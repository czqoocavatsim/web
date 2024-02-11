@extends('layouts.primary')
@section('title', 'Branding - ')
@section('content')
    <div class="container py-4">
        <h1 class="font-weight-bold blue-text">Branding</h1>
        <hr>
        <p>Access our logos and social media icons here. You may use these logos in their published form at your
            discretion.
            We ask however that you do not modify the images from their original, published form. Resizing is permitted,
            provided the aspect ratio remains unadjusted. Please aim to, wherever possible, use the
            blue logo versions (either white on blue or blue on white), and only use the plain white where it is not
            possible or practical to use the blue versions.
            Please contact our Deputy OCA Chief with any queries: <a
                href="mailto:deputy@ganderoceanic.ca">deputy@ganderoceanic.ca</a>
            <br><br>
            The hex colour codes used for our branding are <span class="font-weight-bold black-text">#0080C9
                (Blue)</span> and <span class="font-weight-bold black-text">#FFFFFF (White)</span>.
            The font is <span class="font-weight-bold black-text">Avenir Lt Std</span>.
        </p>
        <h3>Banners</h3>
        <div class="p-2">
            <div class="row">
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_BLUE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_BLUE.png') }}" target="_blank">White
                        on Blue</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_WHITE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_WHITE.png') }}" target="_blank">Blue
                        on White</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_TSPBLUE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_TSPBLUE.png') }}" target="_blank">Blue
                        on Transparent</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img style="background-color: #444444;"
                        src="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_TSPWHITE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_TSPWHITE.png') }}" target="_blank">White
                        on Transparent</a>
                </div>
            </div>
        </div>
        <h3>Squares</h3>
        <div class="p-2">
            <div class="row">
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_BLUE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_BLUE.png') }}" target="_blank">White
                        on Blue</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_WHITE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_WHITE.png') }}" target="_blank">Blue
                        on White</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png') }}" target="_blank">Blue
                        on Transparent</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img style="background-color: #444444;"
                        src="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPWHITE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPWHITE.png') }}" target="_blank">White
                        on Transparent</a>
                </div>
            </div>
        </div>
        <h3>Social</h3>
        <div class="p-2">
            <div class="row">
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_BLUE.png') }}" class="img-fluid"
                        alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_BLUE.png') }}"
                        target="_blank">White on Blue</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_WHITE.png') }}"
                        class="img-fluid" alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_WHITE.png') }}"
                        target="_blank">Blue on White</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_TSPBLUE.png') }}"
                        class="img-fluid" alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_TSPBLUE.png') }}"
                        target="_blank">Blue on Transparent</a>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                    <img style="background-color: #444444;"
                        src="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_TSPWHITE.png') }}"
                        class="img-fluid" alt="">
                    <br>
                    <a href="{{ asset('assets/resources/media/img/brand/social/ZQO_SOCIAL_TSPWHITE.png') }}"
                        target="_blank">White on Transparent</a>
                </div>
            </div>
        </div>
    </div>
@endsection
