@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Instructors</h1>
<p class="my-2">Click on an instructor to view their current students, upcoming sessions, and their contact details.</p>
<div class="list-group">
    @foreach($instructors as $i)
        <a href="{{route('training.admin.instructing.instructors.view', $i->user_id)}}" class="list-group-item list-group-item-action">
            <div class="d-flex flex-row w-100 align-items-center h-100">
                <img src="{{$i->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                <div class="d-flex flex-column h-100">
                    <h5 class="font-weight-bold mb-1">{{$i->user->fullName('FLC')}}</h5>
                    <div>
                        <p class="my-0">{{$i->staffPageTagline()}}&nbsp;&nbsp;•&nbsp;&nbsp;{{count($i->studentsAssigned)}} Students Assigned</p>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>
<ul class="list-unstyled mt-3">
    <li class="mb-2">
        <a href="#" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add an instructor</a>
    </li>
    @can('send announcements')
    <li>
        <a href="{{route('news.announcements.create')}}" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-envelope"></i>&nbsp;&nbsp;Send message to all</a>
    </li>
    @endcan
</ul>
@endsection
