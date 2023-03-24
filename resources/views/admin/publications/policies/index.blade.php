@extends('admin.publications.layouts.main')
@section('title', "View Policies -  ")
@section('publications-content')
    <h1 class="font-weight-bold blue-text pb-2">Policies</h1>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#createPolicyModal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Create policy</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="list-group">
                @if(count($policies) == 0) No policies found @endif
                @foreach($policies as $policy)
                    <div class="list-group-item">
                        <div class="d-flex w-100 flex-row">
                            <h4 class="mr-auto">{{$policy->title}}</h4>
                            <div>
                                <a href="" data-toggle="modal" data-target="#editPolicy{{$policy->id}}Modal" class="blue-text"><i class="fas fa-pen"></i>   Edit</button></a>
                                &nbsp;
                                <a href="" class="red-text" data-toggle="modal" data-target="#deletePolicy{{$policy->id}}Modal"><i class="fa fa-times"></i>   Delete</button></a>
                            </div>
                        </div>
                        <p>{{$policy->description}}</p>
                        <div class="d-flex flex-row">
                            <div>
                                <h6>Created on</h6>
                                <p class="mb-0">{{$policy->created_at->toFormattedDateString()}}</p>
                            </div>
                            <div class="ml-3">
                                <h6>Created by</h6>
                                <p class="mb-0">{{$policy->user->fullName('FLC')}}</p>
                            </div>
                            <div class="ml-3">
                                <h6>URL</h6>
                                <p class="mb-0"><a href="{{$policy->url}}">Link</a></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!--Start create policy modal-->
<div class="modal fade" id="createPolicyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('publications.policies.create.post')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->createPolicyErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->createPolicyErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" value="{{old('title')}}" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" value="{{old('description')}}" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">PDF URL</label>
                        <input type="text" value="{{old('url')}}" name="url" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Create">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End create policy modal-->

@foreach($policies as $p)

<!--Delete policy {{$p->id}} modal-->
<div class="modal fade" id="deletePolicy{{$p->id}}Modal" tabindex="-1" role="dialog">
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
                <a href="{{route('publications.policies.delete', $p->id)}}" role="button" class="btn btn-danger">Delete</a>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End delete policy {{$p->id}} modal-->


<!--Start edit policy {{$p->id}} modal-->
<div class="modal fade" id="editPolicy{{$p->id}}Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit policy "{{$p->title}}"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('publications.policies.edit.post', $p->id)}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->editPolicyErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->editPolicyErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" value="{{old('title', $p->title)}}" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" value="{{old('description', $p->description)}}" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">PDF URL</label>
                        <input type="text" value="{{old('url', $p->url)}}" name="url" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Edit">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End edit policy {{$p->id}} modal-->

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

    if ($.urlParam('createPolicyModal') == '1') {
        $("#createPolicyModal").modal();
    }

    @foreach($policies as $p)

    if ($.urlParam('editPolicy{{$p->id}}Modal') == '1') {
        $("#editPolicy{{$p->id}}Modal").modal();
    }

    @endforeach
</script>

@endsection
