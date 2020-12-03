@extends('training.portal.layouts.main')
@section('page-header-title', 'Your past applications')
@section('page-header-colour', 'blue')
@section('portal-content')

<div class="container py-4">
    @if (count($applications) > 0)
        <div class="list-group z-depth-1">
            @foreach ($applications as $a)
                <li class="list-group-item list-group-item-action waves-effect p-4">
                    <h4 class="font-weight-bold">
                        <a class="blue-text" href="{{route('training.applications.show', $a->reference_id)}}">
                            #{{$a->reference_id}}
                        </a>
                    </h4>
                    <h5>Submitted {{$a->created_at->toDayDateTimeString()}}</h5>
                    <h3>
                        <span class="badge {{$a->statusBadgeHtml()['class']}} rounded shadow-none" style="font-weight: 400;">
                            {!! $a->statusBadgeHtml()['html'] !!}
                        </span>
                    </h3>
                </li>
            @endforeach
        </div>
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
