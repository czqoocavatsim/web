<div class="col-md-4 mb-4">
    <h4 class="font-weight-bold blue-text mb-4">{{\Carbon\Carbon::now()->format('F')}} Top Controllers</h4>
        <ul class="list-unstyled">
            @php $index = 1; @endphp
            @foreach ($topControllers as $c)
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
                                    <img src="{{ $c->user->avatar() }}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                        <div class="d-flex flex-column ml-2">
                                            <h5 class="fw-400">{{ $c->user->fullName('FL') }} 
                                                @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@else<span class="badge bg-primary">CZQO</span>@endif
                                            </h5>
                                            <p>
                                                @if($c->current < 1)
                                                    {{ str_pad(round(($c->current - floor($c->current)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('F')}}
                                                @else
                                                    {{ floor($c->current) }}h {{ str_pad(round(($c->current - floor($c->current)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('F')}}
                                                @endif
                                            </p>
                                        </div>
                                @else
                                <img src="{{asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                <div class="d-flex flex-column ml-2">
                                    <h5 class="fw-400">{{ $c->cid }} 
                                        @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@endif
                                    </h5>
                                    <p>
                                        @if($c->current < 1)
                                            {{ str_pad(round(($c->current - floor($c->current)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('F')}}
                                        @else
                                            {{ floor($c->current) }}h {{ str_pad(round(($c->current - floor($c->current)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('F')}}
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
        @if (count($topControllers) < 1)
            <p style="margin-top: -20px;">No controller connections recorded.</p>
        @endif
    </ul>
</div>