@extends('admin.publications.layouts.main')
@section('title', "View Custom Pages -  ")
@section('publications-content')
    <h1 class="font-weight-bold blue-text pb-2">Custom Pages</h1>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="{{route('publications.custom-pages.create')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Create custom page</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="list-group">
                @if(count($pages) == 0) No custom pages found @endif
                @foreach($pages as $page)
                    <div class="list-group-item">
                        <div class="d-flex w-100 flex-row">
                            <h4 class="mr-auto">{{$page->name}}</h4>
                            <div>
                                <a href="{{route('publications.custom-pages.edit', $page->id)}}" class="blue-text"><i class="fas fa-pen"></i>   Edit</button></a>
                                &nbsp;
                                <a href="" class="red-text" data-toggle="modal" data-target="#delete{{$page->id}}Modal"><i class="fa fa-times"></i>   Delete</button></a>
                            </div>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="ml-0">
                                <p class="mb-0"><a href="/{{$page->slug}}">Public URL</a></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@foreach($pages as $page)

<!--Delete Atc Resource {{$page->id}} modal-->
<div class="modal fade" id="delete{{$page->id}}Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a href="{{route('publications.custom-pages.delete', $page->id)}}" role="button" class="btn btn-danger">Delete</a>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End delete Atc Resource {{$page->id}} modal-->
@endforeach


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
