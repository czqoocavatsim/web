<div class="col-md-4 mb-4">
<h4 class="font-weight-bold blue-text mb-4">{{ \Carbon\Carbon::now()->format('F') }} Top Airlines</h4>
    <ul class="list-unstyled">
    @php $index = 1; @endphp
        @foreach ($topAirlines as $tp)
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
                                <div class="d-flex flex-column ml-2">
                                    <h5 class="fw-400">{{ $tp->info->icao }} | {{ $tp->info->name }}</h5>
                                    <p>{{$tp->current}} flight's flown in {{ \Carbon\Carbon::now()->format('F') }}</p>
                                </div>
                        </span>
                    </p>
                </div>
            </li>
            @php $index++; @endphp
        @endforeach
        
        @if (count($topAirlines) < 1)
            <p style="margin-top: -20px;">No data available.</p>
        @endif
    </ul>
</div>