<div class="col-md-4 mb-4">
    <h4 class="font-weight-bold blue-text mb-4">{{ \Carbon\Carbon::now()->subMonth()->format('Y') }} Top Controllers</h4>
        <ul class="list-unstyled">
        @php $index = 1; @endphp
        @foreach ($yearControllers as $c)
            <li class="mb-1">
                <div class="d-flex flex-row">
                    <span class="font-weight-bold blue-text" style="font-size: 1.9em;">
                        @if ($index == 1)
                            <i class="fas fa-trophy amber-text fa-fw"></i>
                        @elseif ($index == 2)
                            <i class="fas fa-trophy blue-grey-text fa-fw"></i>
                        @elseif ($index == 3)
                            <i class="fas fa-trophy brown-text fa-fw"></i>
                        @else
                            {{ $index }}<sup>th</sup>
                        @endif
                    </span>
                    <p class="mb-0 ml-1">
                        <span style="font-size: 1.4em;">
                            @if($c->user)
                                {{-- Gander Oceanic User Model Exists --}}
                                <img src="{{ $c->user->avatar() }}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                    <div class="d-flex flex-column ml-2">
                                        <h5 class="fw-400">{{ $c->user->fullName('FL') }}
                                            @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@else<span class="badge bg-primary">CZQO</span>@endif
                                        </h5>
                                        <p>
                                            @if($c->currency < 1)
                                                {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                            @else
                                                {{ floor($c->currency) }}h {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                            @endif
                                        </p>
                                    </div>
                            @else
                                {{-- User Model does not Exist --}}
                                <img src="{{asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                <div class="d-flex flex-column ml-2">
                                    <h5 class="fw-400">{{ $c->id }}
                                        @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@endif
                                    </h5>
                                    <p>
                                        @if($c->currency < 1)
                                            {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                        @else
                                            {{ floor($c->currency) }}h {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </span>
                    </p>
                </div>
            </li>
        @php $index++; @endphp
        @endforeach
            @if (count($yearControllers) < 1)
                <p style="margin-top: -20px;">No data available.</p>
            @endif
        </ul>
    </div>