@if (count($controllers) < 1)
    <li class="mb-2">
        <div class="d-flex flex-row justify-content-between align-items-center mb-1">
            <h4 class="m-0 white-text"><i class="fas fa-times" style="margin-right: 1rem;"></i>Hmmmm, weird... There doesn't seem to be any controllers currently connected. Likley a DataFeed Issue, and we are sure it will be resolved shortly!</h4>
        </div>
    </li>
@else
    <div class="row">
        @foreach($controllers as $controller)
            <div class="col-md-2 white-text">
                <p style="margin-bottom: 0px; font-size: 1.6em;">{{$controller->callsign}}</p>
                <p style="margin-bottom: 0px; font-size: 1.1em;">
                    @if ($controller->session_start->diff(\Carbon\Carbon::now())->h > 0)
                        {{ $controller->session_start->diff(\Carbon\Carbon::now())->h }}hr {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                    @else
                        {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                    @endif
                </p>
                <p style="margin-bottom: 15px; font-size: 0.9em;"><i class="fas fa-user"></i>
                    @if($controller->user)
                        {{$controller->user->fullName('FLC')}}
                    @else
                        {{$controller->cid}}
                    @endif
                </p>
            </div>
        @endforeach
    </div>
@endif