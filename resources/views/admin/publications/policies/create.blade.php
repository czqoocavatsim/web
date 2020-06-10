@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('publications.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Publications</a>
    <h1 class="font-weight-bold blue-text">Upload policy</h1>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('publications.policies.create.post')}}" enctype="multipart/form-data">
            @csrf
            @if($errors->createPolicyErrors->any())
            <div class="alert alert-danger">
                <h4>There were errors submitting the policy</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createPolicyErrors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <ul class="stepper mt-0 p-0 stepper-vertical">
                <li class="active">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Primary information</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" required name="title" id="" class="form-control" placeholder="New sector files released, etc.">
                        </div>
                        <div class="form-group">
                            <label for="">PDF URL</label>
                            <input type="text" required name="url" id="" class="form-control" placeholder="https://resources.ganderoceanic.com.....">
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Description</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <label for="">Use Markdown</label>
                        <textarea id="contentMD" name="description" class="w-75"></textarea>
                        <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
                        </script>
                    </div>
                </li>
            </ul>
            <input type="submit" value="Submit" class="btn btn-primary">
        </form>
        </div>
    </div>
</div>
@endsection
