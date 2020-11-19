@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('news.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
    <h1 class="font-weight-bold blue-text">Create Announcement</h1>
    <p style="font-size: 1.2em;">
        An announcement allows you to communicate something to Gander members without making a news article.
    </p>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('news.announcements.create.post')}}" enctype="multipart/form-data">
            @csrf
            @if($errors->createAnnouncementErrors->any())
            <div class="alert alert-danger">
                <h4>There were errors submitting the article</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createAnnouncementErrors->all() as $error)
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
                            <label for="">Announcement title</label>
                            <input type="text" required name="title" id="" class="form-control" placeholder="New sector files released, etc.">
                        </div>
                        <div class="form-group">
                            <label for="">Target group</label>
                            <select name="target_group" id="" class="form-control" required>
                                <option hidden>Select one</option>
                                <option value="everyone">Every user</option>
                                <option disabled value="subscribed">Users subscribed to news notifications</option>
                                <option value="roster">Controller roster</option>
                                <option value="staff">Staff members</option>
                                <option value="students">Current students</option>
                                <option value="instructors">Instructors</option>
                            </select>
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Content of announcement</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <label for="">Use Markdown</label>
                        <textarea id="contentMD" name="content" class="w-75"></textarea>
                        <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
                        </script>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">3</span>
                        <span class="label">GDPR requirements</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <label for="">Why was this announcement sent?</label>
                            <input type="text" required name="reason_for_sending" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Other notes</label>
                            <input type="text" name="notes" id="" class="form-control">
                        </div>
                    </div>
                </li>
            </ul>
            <input type="submit" value="Submit Announcement" class="btn btn-primary">
        </form>
        </div>
    </div>
</div>
@endsection
