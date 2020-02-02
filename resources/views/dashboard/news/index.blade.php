@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="font-weight-bold blue-text">News</h1>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h4>Articles</h4>
            <a href="{{route('news.articles.create')}}" class="mb-3 btn btn-block btn-md waves-effect bg-czqo-blue-light">Create Article</a>
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>Title</th>
                </thead>
                <tbody>
                    @foreach ($articles as $a)
                    <td>
                        <a class="blue-text">{{$a->title}}</a>
                    </td>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('.table.dt').DataTable();
                } );
            </script>
        </div>
        <div class="col-md-6">

        </div>
    </div>
</div>
@endsection