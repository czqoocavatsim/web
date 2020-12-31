@extends('admin.news.layouts.main')
@section('news-content')
    <h1 class="font-weight-bold blue-text pb-2">Articles</h1>
    <ul class="list-unstyled my-3">
        @can('create articles')
        <li class="mb-2">
            <a href="{{route('news.articles.create')}}" class="blue-text fw-600" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Create an article</a>
        </li>
        @endcan
    </ul>
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
<script>
    $(document).ready(function() {
        $('.table.dt').DataTable({ "order": [[ 1, "desc" ]]});
    } );
</script>
@endsection
