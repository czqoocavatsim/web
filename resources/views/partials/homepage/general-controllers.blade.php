@if (count($controllers) < 1)
    <li class="mb-2">
        <div class="d-flex flex-row justify-content-between align-items-center mb-1">
            <h4 class="m-0 white-text"><i class="fas fa-times" style="margin-right: 1rem;"></i>No controllers currently providing OCA Services</h4>
        </div>
    </li>
@else
        <table class="table table-hover" style="color: white; text-align: center;">
            <thead>
                <tr>
                    <th scope="col">Callsign</th>
                    <th scope="col">Controller</th>
                    <th scope="col">Time Online</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($controllers as $controller)
                    <tr>
                        <th scope="row"><b>{{$controller->callsign}}</b>
                            @if($controller->is_instructing == 1)<br><span class="badge bg-danger">Instructing</span>@endif
                            @if($controller->is_student == 1)<br><span class="badge bg-warning">Training</span>@endif
                            @if($controller->is_ctp == 1)<br><span class="badge bg-info">CTP Controller</span>@endif</th>
                        <td>
                            @if($controller->user)
                                {{$controller->user->fullName('FLC')}}
                            @else
                                {{$controller->cid}}
                            @endif
                        </td>
                        <td>
                            @if ($controller->session_start->diff(\Carbon\Carbon::now())->h > 0)
                                {{ $controller->session_start->diff(\Carbon\Carbon::now())->h }}hr {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                            @else
                                {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endif