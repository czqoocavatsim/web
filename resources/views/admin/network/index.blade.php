@extends('layouts.primary')
@section('content')
<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
    <h1 class="blue-text font-weight-bold mt-2">Network</h1>
    <hr>
    <h4 class="font-weight-bold blue-text">Online Hours Per Day (Last 30)</h4>
    <canvas id="hoursPerDayChart" height="100"></canvas>
    <script>
        hoursPerDay = @php echo json_encode($hoursPerDay) @endphp;

        new Chart(document.getElementById("hoursPerDayChart"), {
        type: 'line',
        data: {
            datasets: [
                {
                    label: 'Hours per day',
                    fill: false,
                    borderColor: '#000',
                    data: hoursPerDay
                },
            ],
            labels: Array.from(Array(31).keys()),
        },
        });
    </script>
    <div class="card-deck mt-3">
        <div class="card p-4 bg-czqo-blue-light black-text shadow-none">
            <h3>Monitored Positions</h3>
            <p>Edit monitored positions and view position uptime</p>
            <a class="black-text font-weight-bold" href="{{route('network.monitoredpositions.index')}}">Go <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card p-4 bg-czqo-blue-light black-text shadow-none">
            <h3>Controller Activity</h3>
            <p>View controller activity statistics against policy requirements</p>
            <a class="black-text font-weight-bold" href="#">Go <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card p-4 bg-czqo-blue-light black-text shadow-none">
            <h3>Overall Statistics</h3>
            <p>View total statistics for Gander positions</p>
            <a class="black-text font-weight-bold" href="#">Go <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>
@endsection
