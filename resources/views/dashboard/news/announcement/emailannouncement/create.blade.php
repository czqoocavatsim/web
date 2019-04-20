@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <h2>Send Email Announcement</h2><hr>
{!! Form::open(['route' => 'emailannouncement.store']) !!}

<div class="form-group">
    {!! Form::textarea('msg', null, ['class' => 'form-control', 'id' => 'msg']) !!}
</div>
    <script>
        tinymce.init({
            selector: '#msg',
            plugins: 'link media table',
            menubar: 'edit insert format'
        });
    </script>

@if ($errors->any())
    <div class="alert alert-danger">
        <h4 class="alert-heading">There were errors submitting your feedback.</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
</div>
@stop
