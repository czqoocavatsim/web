@extends('admin.publications.layouts.main')
@section('title', "Create Custom Page -  ")
@section('publications-content')
    <a href="{{route('publications.custom-pages')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Custom Pages</a>
    <form action="{{route('publications.custom-pages.post.create')}}" method="POST" id="loginForm">
        @csrf
        <br>
        @if($errors->createCustomPageError->any())
            <div class="alert alert-danger">
                <h4>There were errors in creating a custom page</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createCustomPageError->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <br>
        @endif
        <div class="form-group">
            <label for="">Custom Page Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group mt-3">
            <label>Content of Custom Page</label>
            <textarea id="contentMD" name="content" style="display:none; height:" ></textarea>
            <script>
                var simplemde = new EasyMDE({ maxHeight: '200px', autofocus: true, autoRefresh: true, element: document.getElementById("contentMD")});
            </script>
        </div>
        <div class="form-group">
            <label for="">Allow Form Responses</label>
            <select name="responses" required class="form-control">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <button class="btn btn-success mt-4" style="font-size: 1.1em;"><i class="fas fa-check"></i>&nbsp;&nbsp;Create</button>
        <script>
        loginForm.addEventListener("submit", (e) => {
            document.getElementById('contentMD').value = simplemde.markdown(simplemde.value());
        });
        </script>

    </form>
@endsection
