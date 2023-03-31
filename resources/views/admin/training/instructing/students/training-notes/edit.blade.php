@extends('admin.training.layouts.main')
@section('title', "Edit Training Note - Student {$student->user->fullName('FLC')} - ")
@section('training-content')
    <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> {{$student->user->fullName('F')}}'s Training Notes</a>
    <div class="d-flex flex-row align-items-center mt-3">
        <div>
            <h2 class="blue-text mt-2 mb-1">Edit training note</h2>
        </div>
    </div>
    @if ($errors->editTrainingNoteErrors->any())
    <div class="alert red lighten-1 mt-3 z-depth-1">
        <ul class="mb-0 list-unstyled">
            @foreach ($errors->editTrainingNoteErrors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{route('training.admin.instructing.students.records.training-notes.post.edit',[$student->user_id, $note->id])}}" method="POST">
        @csrf
        <div class="form-group mt-3">
            <label>Content of the note</label>
            <textarea id="contentMD" name="content" style="display:none; height:">{{ $note->content }}</textarea>
            <script>
                var simplemde = new EasyMDE({ maxHeight: '200px', autofocus: true, autoRefresh: true, element: document.getElementById("contentMD")});
            </script>
        </div>
        <div class="form-group">
            <label for="">Visibility</label>
            <select name="visibility" required class="form-control">
                @if ($note->staff_only == 0)
                <option value="0" selected>Visible to student</option>
                <option value="1">Visible to staff only</option>
                @else
                <option value="0">Visible to student</option>
                <option value="1" selected>Visible to staff only</option>
                @endif 
            </select>
        </div>
        <button class="btn btn-success mt-4" style="font-size: 1.1em;"><i class="fas fa-check"></i>&nbsp;&nbsp;Edit Note</button>
    </form>
@endsection
