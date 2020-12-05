@extends('admin.publications.layouts.main')
@section('publications-content')
    <h1 class="font-weight-bold blue-text pb-2">Custom Pages</h1>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Create custom page</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="list-group">
                @if(count($pages) == 0) No custom pages found @endif
                @foreach($pages as $page)
                    <a class="list-group-item list-group-item-action waves-effect" href="{{route('publications.custom-pages.view', $page->slug)}}">
                        <div class="d-flex w-100 flex-row">
                            <h4 class="mr-auto">{{$page->name}}</h4>
                        </div>
                        <p>{{$page->description}}</p>
                        <div class="d-flex flex-row">
                            <div class="ml-0">
                                <h6>URL</h6>
                                <p class="mb-0">{{route('publications.custompages.view', $page->slug)}}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
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
