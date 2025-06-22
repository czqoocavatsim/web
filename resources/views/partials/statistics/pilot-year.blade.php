<div class="col-md-4 mb-4">
<h4 class="font-weight-bold blue-text mb-4">{{ \Carbon\Carbon::now()->format('Y') }} Top Pilots</h4>
    <ul class="list-unstyled">
    @php $index = 1; @endphp
        @foreach ($yearPilot as $tp)
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
                            @if($tp->user)
                                <img src="{{ $tp->user->avatar() }}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                <div class="d-flex flex-column ml-2">
                                    <h5 class="fw-400">{{ $tp->user->fullName('FL') }}</h5>
                                    <p>{{$tp->year}} flight's flown in {{ \Carbon\Carbon::now()->format('Y') }}</p>
                                </div>
                            @else
                                <img src="{{asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                    <div class="d-flex flex-column ml-2">
                                        <h5 class="fw-400">{{ $tp->cid }}</h5>
                                        <p>{{$tp->year}} @if($tp->year == 1)flight @else flight's @endif flown in {{ \Carbon\Carbon::now()->format('Y') }}</p>
                                    </div>
                            @endif
                        </span>
                    </p>
                </div>
            </li>
            @php $index++; @endphp
        @endforeach
        
        @if (count($yearPilot) < 1)
            <p style="margin-top: -20px;">No data available.</p>
        @endif
    </ul>
</div>