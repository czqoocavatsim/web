
@section('title', 'Error 500 - ')

@section('content')
    <div class="container py-5">
        <h1 class="font-weight-bold blue-text">Looks like something broke...</h1>
        <h4 class="font-weight-bold">ERROR 500</h4>
        <div class="mt-4">
            <p style="font-size: 1.2em;">
                Please report this error with the details in the box below to the Web Team.
            </p>
            <p class="border p-3" style="font-family: monospace;">
                {{Request::url()}}<br/>
                {{Carbon\Carbon::now()}}
                {{$exception->getMessage()}}
            </p>
        </div>
        <a href="{{route('index')}}" class="btn bg-czqo-blue-light">Go Home</a>
    </div>
@endsection
