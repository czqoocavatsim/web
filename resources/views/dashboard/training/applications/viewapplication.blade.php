@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <h2>View Application #{{ $application->application_id }}</h2>
        <h5><a href="{{route('users.viewprofile', $application->user->id)}}">{{$application->user->fullName('FLC')}}</a></h5>
        <br/>
        <h6>Status</h6>
        @if ($application->status == 0)
            <p class="mb-1 text-info">
                <i class="fa fa-clock"></i>&nbsp;
                Pending
            </p>
        @elseif ($application->status == 2)
            <p class="mb-1 text-success">
                <i class="fa fa-check"></i>&nbsp;
                Accepted
            </p>
        @elseif ($application->status == 1)
            <p class="mb-1 text-danger">
                <i class="fa fa-times"></i>&nbsp;
                Denied
            </p>
        @elseif ($application->status == 3)
            <p class="mb-1 text-dark">
                <i class="fa fa-times"></i>&nbsp;
                Withdrawn
            </p>
        @endif
        <p class="mb-1">
            Processed by:
            @if (!$application->processed_by)
            no one yet.
            @else
            {{\App\Models\Users\User::find($application->processed_by)->fullName('FLC')}}
            @endif
        </p>
        <p class="mb-1">
            Processed at:
            @if (!$application->processed_at)
            not yet processed.
            @else
            {{$application->processed_at}}
            @endif
        </p>
        <br/>
        <script>
            tinymce.init({
                selector: '#staffCommentsField',
                menubar: 'false',
                setup : function(ed) {
                    ed.on('blur', function(e) {
                        showSaveButton();
                    });
                },
            });
        </script>
        <h6>Comments from Staff</h6>
        <small>Only Executive members may edit comments.</small>
        {!! Form::open(['route' => ['training.application.savestaffcomment', $application->application_id]]) !!}
        {!! Form::textarea('staff_comment', $application->staff_comment, ['class' => 'form-control', 'onblur' => 'showSaveButton()', 'id' => 'staffCommentsField']) !!}
        <small>This comment will be visible to the applicant and will be included in the application denied email sent if the application is denied.</small>
        <br/>
        {!! Form::submit('Save Staff Comments', ['class' => 'btn btn-sm btn-success invisible', 'id' => 'saveCommentsButton']) !!}
        @if (Auth::user()->permissions >= 4)
        <script type="text/javascript">
            function showSaveButton() {
                document.getElementById('saveCommentsButton').classList.remove('invisible');
                console.log('Save staff comments button visible!');
            }
        </script>
        @endif
        {!! Form::close() !!}
        <br/>
        <h6>Applicant Statement</h6>
        <div class="border p-2">
            <p>{!! html_entity_decode($application->applicant_statement) !!}</p>
        </div>
        <br/>
        <h6>Submitted at {{ $application->submitted_at }} Zulu</h6>
        <br/>
        <div>
            <a class="btn btn-secondary" href="{{url('dashboard/training/applications')}}">
                Go back
            </a>
            @if ($application->status == 0 && Auth::user()->permissions >= 4)
                <a class="btn btn-success" href="{{url('dashboard/training/applications/' . $application->application_id . '/accept')}}">
                    Accept
                </a>
                <a class="btn btn-danger" href="{{url('dashboard/training/applications/' . $application->application_id . '/deny')}}">
                    Deny
                </a>
            @endif
            <a href="https://stats.vatsim.net/search_id.php?id={{$application->user->id}}" target="_blank" class="btn btn-info">View VATSIM Stats</a>
        </div>
        <br/>
    </div>
@stop
