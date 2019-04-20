@extends('layouts.master')

@section('navbarprim')
    @parent
@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Create News Item</h2>
        <br/>
        {!! Form::open(['route' => 'news.store']) !!}
        <div class="form-group">
            <label>Type</label>
            {!! Form::select('type', ['Email' => 'News and email all users', 'CertifiedOnly' => 'News and email CZQO certified controllers', 'NoEmail' => 'News but no email', 'Certification' => 'New controller certification'], ['placeholder' => 'Please choose one...'], ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label>Title</label>
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label>Content</label>
            {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'content']) !!}
            <script>
                tinymce.init({
                    selector: '#content',
                    plugins: 'link media table',
                    menubar: 'edit insert format'
                });
            </script>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <h4 class="alert-heading">There were errors submitting the item.</h4>
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