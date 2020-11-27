@extends('layouts.master', ['adminNavBar'=>true])
@section('content')
<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
    <h1 class="font-weight-bold blue-text">News</h1>
    <hr>
    <div class="row">
        @can('view articles')
        <div class="col-md-6">
            <h4>Articles</h4>
            @can('create articles')
            <a href="{{route('news.articles.create')}}" class="mb-3 btn btn-block btn-md waves-effect bg-czqo-blue-light">Create Article</a>
            @endcan
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Date Published</th>
                </thead>
                <tbody>
                    @foreach ($articles as $a)
                    <tr>
                        <td><a class="blue-text" href="{{route('news.articles.view', $a->slug)}}">{{$a->title}}</a></td>
                        <td data-order="{{$a->published}}">{{$a->published->toDayDateTimeString()}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcan
        @can('send announcements')
        <div class="col-md-6">
            <h4>Announcements</h4>
            <a href="{{route('news.announcements.create')}}" class="mb-3 btn btn-block btn-md waves-effect bg-czqo-blue-light">Create Announcement</a>
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Date Published</th>
                </thead>
                <tbody>
                    @foreach ($announcements as $a)
                    <tr>
                        <td><a class="blue-text" href="{{route('news.announcements.view', $a->slug)}}">{{$a->title}}</a></td>
                        <td data-order="{{$a->created_at}}">{{$a->created_at->toDayDateTimeString()}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcan
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.table.dt').DataTable({ "order": [[ 1, "desc" ]]});
    } );
</script>
@endsection
