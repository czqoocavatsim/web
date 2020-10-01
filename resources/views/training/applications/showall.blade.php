@extends('layouts.master')

@section('content')

<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
    <h1 class="font-weight-bold blue-text">Your applications</h1>
    <hr>
    @if (count($applications) > 0)
        @foreach ($applications as $a)
            <ul class="list-unstyled pl-0 ml-0">
                <li class="card p-4">
                    <h4 class="font-weight-bold">
                        <a class="blue-text" href="{{route('training.applications.show', $a->reference_id)}}">
                            #{{$a->reference_id}}
                        </a>
                    </h4>
                    <h5>Submitted {{$a->created_at->toDayDateTimeString()}}</h5>
                    <h3>
                        <span class="badge {{$a->statusBadgeHtml()['class']}} rounded shadow-none">
                            {!! $a->statusBadgeHtml()['html'] !!}
                        </span>
                    </h3>
                </li>
            </ul>
        @endforeach
    @else
        You have not made an application.
    @endif
    @can('start-application')
    <div class="mt-5">
        <a href="{{route('training.applications.apply')}}" class="blue-text" style="font-size: 1.2em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Start an application</a>
    </div>
    @endcan
</div>

@endsection
