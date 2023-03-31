@extends('admin.training.layouts.main')
@section('title', "Training Notes - Student {$student->user->fullName('FLC')} - ")
@section('training-content')
    <a href="{{route('training.admin.instructing.students.view', $student->user_id)}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> {{$student->user->fullName('FL')}}</a>
    <div class="d-flex flex-row align-items-center mt-3">
        <img src="{{$student->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
        <div>
            <h2 class="blue-text mt-2 mb-1">{{$student->user->fullName('F')}}'s Training Notes</h2>
        </div>
    </div>

    @if (count($notes) == 0)
        <div class="mt-4">
        No notes found.
        </div>
    @endif

    <div class="list-group list-group-flush z-depth-1 rounded mt-4">
        @foreach ($notes as $note)
        <div class="list-group-item">
            <div class="d-flex flex-row justify-content-between">
                <div>{{$note->created_at->toFormattedDateString()}}</div>
                <div>
                    <a data-policy-id="{{$note->id}}" href="javascript:void(0)" class="expandHidePolicyButton"><i class="fa fa-eye"></i>&nbsp;View</a>
                </div>
            </div>
            <div class="d-none" id="policyEmbed{{$note->id}}">
                <div class="d-flex flex-row justify-content-between">
                    <p class="text-muted mt-3">Added by {{$note->instructor->user->fullName('FLC')}}, last edited <span style="text-decoration: underline; text-decoration-style:dotted; cursor: help;" title="{{$note->updated_at ?? ''}}">{{$note->updated_at ? $note->updated_at->diffForHumans() : 'never'}}</span>, {{$note->staff_only ? 'staff only' : 'visible to student'}}</p>
                    <p class="mt-3">
                        <a class="text-muted" href="{{route('training.admin.instructing.students.records.training-notes.edit', [$student->user_id, $note->id])}}"><i class="fa fa-edit"></i>&nbsp;Edit</a>
                        <a data-toggle="modal" data-target="#deleteNote{{$note->id}}Modal" class="red-text ml-2"><i class="fa fa-trash-alt"></i>&nbsp;Delete</a>
                    </p>
                </div>
                <hr>
                <p>
                    {{$note->contentHtml()}}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <ul class="list-unstyled mt-4">
        <li class="mb-2">
            <a href="{{route('training.admin.instructing.students.records.training-notes.create', $student->user_id)}}" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add a note</a>
        </li>
    </ul>

    <h4 class="blue-text mt-5" id="instructorRecommendations">Instructor Recommendations</h4>
    <div class="list-group list-group-flush z-depth-1 rounded mt-4">
        @foreach ($recommendations as $note)
        <div class="list-group-item">
            <div class="d-flex flex-row justify-content-between">
                <div>{{$note->created_at->toFormattedDateString()}} - {{$note->type}}</div>
                <div>
                    <a data-policy-id="{{$note->id}}9999" href="javascript:void(0)" class="expandHidePolicyButton"><i class="fa fa-eye"></i>&nbsp;View</a>
                </div>
            </div>
            <div class="d-none" id="policyEmbed{{$note->id}}9999">
                <div class="d-flex flex-row justify-content-between">
                    <p class="text-muted mt-3">Added by {{$note->instructor->user->fullName('FLC')}}, last edited <span style="text-decoration: underline; text-decoration-style:dotted; cursor: help;" title="{{$note->updated_at ?? ''}}">{{$note->updated_at ? $note->updated_at->diffForHumans() : 'never'}}</span></p>
                </div>
                <hr>
                <p>
                    {{$note->type}}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    @if (count($recommendations) == 0)
        <div class="mt-4">
        No recommendations found. Issue a recommendation on the student profile page.
        </div>
    @endif

    <h4 class="blue-text mt-5" id="history">History</h4>
    <div class="list-group list-group-flush z-depth-1 rounded mt-4">
        TBA
    </div>

    <!--Delete modal-->
    @foreach($notes as $n)
    <div class="modal fade" id="deleteNote{{$n->id}}Modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <a href="{{route('training.admin.instructing.students.records.training-notes.delete', [$student->user_id, $n->id])}}" role="button" class="btn btn-danger"><i class="fa fa-trash-alt mr-2"></i>Delete</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!--End delete modal-->


    <script>
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results[1] || 0;
        }

        if ($.urlParam('assignInstructorModal') == '1') {
            $("#assignInstructorModal").modal();
        }
    </script>
@endsection
