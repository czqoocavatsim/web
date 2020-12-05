@extends('layouts.master')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-center flex-column">
        <h2 class="font-weight-bold blue-text">Welcome to Gander Oceanic!</h2>
        <p class="text-center mt-2" style="font-size: 1.20em;">Some first steps to get started:</p>
        <form action="{{route('privacyaccept')}}" class="d-flex flex-center flex-column" method="POST">
            @csrf
            <ul class="list-unstyled mt-4" style="width: 66%;">
                <li class="w-100">
                    <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                        <div class="d-flex flex-row">
                            <i style="font-size:35px; margin-right:20px;" class="fas fa-envelope blue-text"></i>
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Opt into email updates on what's happening at Gander Oceanic</p>
                                <p class="text-muted mt-2" style="width: 75%; text-align:left; font-size: 1em;">These can include certified controllers each month, updates from the staff team, and the latest events</p>
                                <div class="custom-control custom-checkbox mt-2" style="text-align:left;">
                                    <input name="optInEmails" type="checkbox" class="custom-control-input" id="defaultUnchecked">
                                    <label class="custom-control-label" for="defaultUnchecked">Opt into emails (you can opt out at any time)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="w-100">
                    <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                        <div class="d-flex flex-row">
                            <i style="font-size:35px; margin-right:20px;" class="fab fa-discord blue-text"></i>
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Join our Discord community to chat with our Gander Oceanic controller and pilot community</p>
                                <p class="text-muted mt-2" style="width: 75%; text-align:left; font-size: 1em;">Click the Discord button on the navigation bar after continuing</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="w-100">
                    <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                        <div class="d-flex flex-row">
                            <i style="font-size:35px; margin-right:20px;" class="fas fa-user-friends blue-text"></i>
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Check out the resources available on our website</p>
                                <p class="text-muted mt-2" style="width: 75%; text-align:left; font-size: 1em;">You can view pilot tools, upcoming events, and help for oceanic controlling</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <p class="text-center mt-2" style="font-size: 1em;">Please accept our <span data-toggle="modal" data-target="#privacyModal" style="text-decoration: underline; text-decoration-style:dotted;">Privacy Policy</span> to continue</p>
            <div class="d-flex flex-row">
                <a href="/privacydeny" class="btn btn-light mt-3">Log Out</a>
                <input type="submit" class="btn btn-success mt-3" value="Accept Privacy Policy">
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe style="border: none; margin-top: 10px; margin-bottom: 10px; width: 100%; height: 100vh;" src="https://cdn.ganderoceanic.com/resources/files/policy/CZQOP3R3_Privacy.pdf"></iframe>
                If the PDF is not displaying correctly, you can view it directly <a href="https://cdn.ganderoceanic.com/resources/files/policy/CZQOP3R3_Privacy.pdf">here.</a>
            </div>
        </div>
    </div>
</div>
@endsection
