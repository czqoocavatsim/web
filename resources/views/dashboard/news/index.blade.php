@extends('layouts.master')
@section('content')
<div class="container py-4">
    <h2>News</h2>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h4>Articles</h4>
            <a href="{{route('news.articles.create')}}" class="mb-3 btn btn-block btn-md waves-effect btn-primary">Create Article</a>
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
