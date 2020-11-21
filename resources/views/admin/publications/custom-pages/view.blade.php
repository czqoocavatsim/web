@extends('admin.publications.layouts.main')
@section('publications-content')
    <a href="{{route('publications.custom-pages')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Custom Pages</a>
    <h1 class="font-weight-bold blue-text pb-2">{{$page->name}}</h1>
</div>


<script>
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        try {
        return results[1] || 0;
        } catch {
            return 0;
        }
    }

</script>

@endsection
